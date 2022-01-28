<?php

namespace App\View\Components\Admin\CalendarEvent\User;

use App\Models\CalendarEvent;
use App\Models\CalendarEventUserStatus;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Status extends Component
{
    private CalendarEvent $calendarEvent;
    private User $user;

    /**
     * Collection from array that is created from CalendarEventStatuses model collection
     * As we can't pass here the reference to this object, as it is done in the new requests.
     *
     * To avoid having multiple DB queries for every calendar status, we get the array with data
     * from CalendarEventController@show
     *
     * This component will submit either status or info for easier update UX
     *
     * @var Collection
     */
    private Collection $userStatuses;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(CalendarEvent $calendarEvent, User $user, array $userStatuses)
    {
        $this->calendarEvent = $calendarEvent;
        $this->user = $user;
        $this->userStatuses = collect($userStatuses);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $calendarEventUserStatusArray = $this->userStatuses->isNotEmpty()
            ? $this->userStatuses->where('user_id', $this->user->id)->first()
            : null;
        $status = $calendarEventUserStatusArray ? $calendarEventUserStatusArray['status'] : null;
        $info = $calendarEventUserStatusArray ? $calendarEventUserStatusArray['info'] : null;

        return view('components.admin.calendar-event.user.status', [
            'status' => old('status', $status),
            'statusOptions' => CalendarEventUserStatus::getStatuses(),
            'info' => old('info', $info),
            'infoOptions' => CalendarEventUserStatus::getInfoOptions(),
            'route' => route('admin.calendar-events.users.status.update', [$this->calendarEvent, $this->user])
        ]);
    }
}
