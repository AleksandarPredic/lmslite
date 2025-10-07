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
        ], [
            'cal_event_compensation_calendar_event_user_status_id.required' => 'Error, the event status is required.',
            'cal_event_compensation_calendar_event_user_status_id.exists'   => 'Error, the event status is invalid.',
            'cal_event_compensation_user_id.required'                       => 'Error, the user is required.',
            'cal_event_compensation_user_id.exists'                         => 'Error, the selected user is invalid.',
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param CalendarEvent $calendarEvent
     * @param CalendarEventUserCompensation $compensation
     *
     * @return RedirectResponse
     */
    public function update(Request $request, CalendarEvent $calendarEvent, CalendarEventUserCompensation $compensation)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'string', Rule::in(array_merge(CalendarEventUserCompensation::getStatusEnumValues(), ['none']))],
            'note'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Sanitize note if present
        if (isset($validated['note'])) {
            $validated['note'] = strip_tags($validated['note']);
        }

        // Prevent saving none
        if ($validated['status'] === 'none') {
            unset($validated['status']);
        }

        $compensation->update($validated);

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Compensation updated for the user %s',
                $compensation->user->name
            )
        );
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
