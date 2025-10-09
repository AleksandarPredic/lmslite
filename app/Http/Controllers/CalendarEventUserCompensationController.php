<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\CalendarEventUserCompensation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CalendarEventUserCompensationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param CalendarEvent $calendarEvent
     *
     * @return RedirectResponse
     */
    public function store(Request $request, CalendarEvent $calendarEvent)
    {
        $validator = Validator::make($request->all(), [
            'cal_event_compensation_calendar_event_user_status_id' => 'required|exists:calendar_event_user_statuses,id',
            'cal_event_compensation_user_id'                       => 'required|exists:users,id',
            'cal_event_compensation_payment_completed'             => 'required|string|in:yes,no'
        ], [
            'cal_event_compensation_calendar_event_user_status_id.required' => 'Error, the event status is required.',
            'cal_event_compensation_calendar_event_user_status_id.exists'   => 'Error, the event status is invalid.',
            'cal_event_compensation_user_id.required'                       => 'Error, the user is required.',
            'cal_event_compensation_user_id.exists'                         => 'Error, the selected user is invalid.',
            'cal_event_compensation_payment_completed.required'                          => 'Error, the paid field is required.',
            'cal_event_compensation_payment_completed.string'                           => 'Error, the paid field must be yes or no value.',
        ]);

        // Return manually with key as on the frontend we are getting the error without key names for the fields
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'compensation')->withInput();
        }

        $validated = $validator->validated();

        $user                = User::find($validated['cal_event_compensation_user_id']);
        $calendarEventUserId = $validated['cal_event_compensation_calendar_event_user_status_id'];

        // Before creating, check if we don't have this already
        $compensation = CalendarEventUserCompensation::where([
            'calendar_event_user_status_id' => $calendarEventUserId,
            'calendar_event_id'             => $calendarEvent->id,
            'user_id'                       => $user->id,
        ])->first();

        if ($compensation) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    'Error, this compensation already exists for the user %s. You should talk to the support.',
                    $user->name
                )
            );
        }

        CalendarEventUserCompensation::create([
            'calendar_event_user_status_id' => $calendarEventUserId,
            'calendar_event_id'             => $calendarEvent->id,
            'user_id'                       => $user->id,
            'status'                        => null, // Initially null, can be updated later
            'free'                          => $validated['cal_event_compensation_payment_completed'] !== 'yes',
            'payment_completed'             => false, // Initially null, can be updated later
        ]);

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Compensation added for the user %s',
                $user->name
            )
        );
    }

    /**
     * Update the status and payment completed for compensation
     * Triggered via ajax in resources/js/calendar-event/CalendarEventUpdateCompensation.js
     *
     * @param \Illuminate\Http\Request $request
     * @param CalendarEvent $calendarEvent
     * @param CalendarEventUserCompensation $compensation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, CalendarEvent $calendarEvent, CalendarEventUserCompensation $compensation)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'string', Rule::in(array_merge(CalendarEventUserCompensation::getStatusEnumValues(), ['none']))],
            'payment_completed'   => 'nullable|string|in:yes,no'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Sanitize note if present
        if (isset($validated['payment_completed'])) {
            $validated['payment_completed'] = $validated['payment_completed'] === 'yes';
        }

        // Prevent saving none and allow reverting back to none
        if ($validated['status'] === 'none') {
            $validated['status'] = null;
        }

        $compensation->update($validated);

        return response()->json([
            'message' => sprintf(
                'Compensation updated for the user %s',
                $compensation->user->name
            )
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CalendarEvent $calendarEvent
     * @param CalendarEventUserCompensation $compensation
     *
     * @return RedirectResponse
     */
    public function destroy(CalendarEvent $calendarEvent, CalendarEventUserCompensation $compensation)
    {
        // We have $calendarEvent var here only to keep the route consistent with othe CRUD operations, we don't use it
        $resultBool = $compensation->delete();
        $user       = $compensation->user;

        if ( ! $resultBool) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    'Error, something went wrong removing compensation for the user %s. You should talk to the support.',
                    $user->name
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Compensation removed for the user %s',
                $user->name
            )
        );
    }
}
