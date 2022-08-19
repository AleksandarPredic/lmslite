/**
 * JS for the component resources/views/components/admin/calendar-event/user/select-field.blade.php
 *
 * Ajax updates for the user status on the calendar event
 */
export default class CalendarEventStatusUpdate {
    constructor(status) {
        const selectField = status.querySelector('select');
        const form = selectField.parentNode;
        const userItemStatusParentElement = form.parentNode;
        const message = userItemStatusParentElement.querySelector('.cal-event-user-status__message');
        const userItemParentElement = userItemStatusParentElement.parentNode.parentNode;

        status.querySelector('select').addEventListener('change', (event) => {
            this.statusChanged(
                message,
                selectField,
                form.action,
                userItemParentElement
            );
        });
    }

    statusChanged = (message, selectField, routeUrl, userItemParentElement) => {
        const errorBackgroundColor = 'rgba(255, 0, 0, 0.8)';
        const successBackgroundColor = 'rgba(0, 117, 0, 0.6)';
        const successUserItemParentElementCssClass = 'singular-meta__item-user-attended';

        const updateMessage = (text, error) => {
            message.innerText = text;
            if (error) {
                selectField.style.backgroundColor = errorBackgroundColor;
            } else {
                selectField.style.backgroundColor = successBackgroundColor;
            }

            if (! error) {
                setTimeout(() => {
                    message.innerText = '';
                    selectField.style.backgroundColor = '';
                }, 3000);
            }
        }
        message.innerText = 'saving...';

        selectField.disabled = true;

        const data = {};
        data[selectField.name] = selectField.value;

        axios.patch(routeUrl, data)
            .then(function (response) {

                updateMessage(response.data.message, false);
                selectField.disabled = false;

                // After successfull update, if the updated status is attended,mark the whole user meta section green
                if (data.hasOwnProperty('status') && data.status === 'attended') {
                    userItemParentElement.classList.add(successUserItemParentElementCssClass);
                } else {
                    if (userItemParentElement.classList.contains(successUserItemParentElementCssClass)) {
                        userItemParentElement.classList.remove(successUserItemParentElementCssClass);
                    }
                }
            })
            .catch(function (error) {
                console.log(error.response);

                if (error.response && error.response.data && error.response.data.message) {
                    updateMessage(error.response.data.message, true);
                    return;
                }

                updateMessage('Ooops! Error!', true);
            });
    }
}
