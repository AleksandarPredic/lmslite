/**
 * JS for handling compensation user search and selection
 *
 * @see resources/views/admin/calendar-event/show.blade.php
 */
import axios from 'axios';

export default class CalendarEventAddCompensation {
    constructor() {
        this.searchInput = document.getElementById('find-compensation-user');
        this.userSelect = document.querySelector('.cal-event-compensation__user-select select');

        this.searchUsersRoute = this.searchInput ? this.searchInput.dataset.routeusers : null;
        this.searchStatusesRoute = this.searchInput ? this.searchInput.dataset.routestatuses : null;
        this.excludeUserIds = this.searchInput ? this.searchInput.dataset.exclude : null;
        this.calendarEventId = this.searchInput ? this.searchInput.dataset.calendareventid : null;
        this.statusesMessage = document.querySelector('.cal-event-compensation__statuses-message');
        this.statusesList = document.querySelector('.cal-event-compensation__statuses-list');
        this.ajaxErrorMessage = document.querySelector('.cal-event-compensation__ajax-error-msg');
        this.debounceTimeout = null;
        this.debounceDelay = 500;

        // Hidden inputs and form
        this.form = document.querySelector('.cal-event-compensation__form');
        this.hiddenUserIdInput = document.getElementById('cal_event_compensation_user_id');
        this.hiddenSUserStatusIdInput = document.getElementById('cal_event_compensation_calendar_event_user_status_id');

        this.init();
    }

    init() {
        if (!this.searchInput || !this.userSelect || !this.searchUsersRoute) {
            return;
        }

        /* User search events */
        this.searchInput.addEventListener('input', this.handleUserSearch.bind(this));

        /* Statuses search events */
        this.userSelect.addEventListener('change', this.handleStatusesSearch.bind(this));
    }

    /* SEARCHING AND HANDLING USER SEARCH */

    handleUserSearch(event) {
        const searchTerm = event.target.value.trim();

        // Clear previous timeout
        clearTimeout(this.debounceTimeout);

        // Set a new timeout
        this.debounceTimeout = setTimeout(() => {
            if (searchTerm.length < 3) {
                this.updateSelectOptions([], true);
                return;
            }

            this.searchUsers(searchTerm);
        }, this.debounceDelay);
    }

    async searchUsers(searchTerm) {
        try {
            const response = await axios.post(this.searchUsersRoute, {
                name: searchTerm,
                exclude: this.excludeUserIds ? this.excludeUserIds.split(',') : []
            });

            if (response.data) {
                this.updateSelectOptions(response.data, false);
            }
        } catch (error) {
            console.error('Error searching users:', error);
            this.updateSelectOptions([], true);
            this.ajaxErrorMessage.textContent = 'Something went wrong searching users. Please contact the support';
        }
    }

    updateSelectOptions(users, setWaiting) {
        // Clear current options
        this.userSelect.innerHTML = '';

        if (setWaiting) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'Waiting...';
            this.userSelect.appendChild(option);
            return;
        }

        if (users.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No users with appropriate statuses found or user already added here';
            this.userSelect.appendChild(option);
            return;
        }

        // Add placeholder option
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = 'Select a user';
        this.userSelect.appendChild(placeholderOption);

        // Add user options
        users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = `${user.name} - ID:${user.id}`;
            this.userSelect.appendChild(option);
        });
    }

    /* SEARCHING AND HANDLING STATUSES */

    handleStatusesSearch(event) {
        const userId = Number(event.target.value);

        this.searchStatuses(userId);
    }

    async searchStatuses(userId) {
        try {
            const response = await axios.post(this.searchStatusesRoute, {
                user_id: userId,
                calendar_event_id: this.calendarEventId
            });

            if (response.data) {
                console.log(response.data)
                this.displayUserStatuses(response.data, userId);
            }
        } catch (error) {
            console.error('Error searching users:', error);
            this.updateSelectOptions([], true);
            this.ajaxErrorMessage.textContent = 'Something went wrong searching statuses. Please contact the support';
        }
    }

    displayUserStatuses(responseData, userId) {
        const statusesContainer = this.statusesList;

        // Clear any existing content
        statusesContainer.innerHTML = '';

        // Create the list element
        const ul = document.createElement('ul');
        ul.classList.add('statuses-list');

        // Check if we have data to display
        if (responseData && responseData.length > 0) {
            // Create list items for each status
            responseData.forEach((item, index) => {
                const li = document.createElement('li');
                li.classList.add('status-item', 'status-item--item', 'mb-4', 'p-2', 'border', 'rounded', 'bg-indigo-100');
                li.dataset.statusId = item.id; // Store the status ID if available

                li.innerHTML = `
                <div class="mb-1"><strong>Event: ${item.event}</strong></div>
                <div class="mb-1"><span>Date:</span> ${item.calendar_event_date}</div>
                <div><span>Status:</span> ${item.status}</div>`;

                // Add click event listener to each list item
                li.addEventListener('click', () => this.populateHiddenInputs(item.status_id, userId));

                // Add the list item to the list
                ul.appendChild(li);
            });
        } else {
            // No statuses available
            const li = document.createElement('li');
            li.classList.add('status-item', 'status-item--empty', 'p-2', 'border', 'rounded', 'bg-gray-800', 'text-white');
            li.textContent = 'No eligible statuses found';
            ul.appendChild(li);
        }

        // Append the list to the container
        statusesContainer.appendChild(ul);
    }

    populateHiddenInputs(statusId, userId) {
        console.log(statusId, userId)
        this.hiddenUserIdInput.value = userId;
        this.hiddenSUserStatusIdInput.value = statusId;
        this.form.submit();
    }
}
