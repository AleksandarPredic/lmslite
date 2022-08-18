/**
 * JS for the component resources/views/components/admin/calendar-event/user/select-field.blade.php
 *
 * Ajax updates for the user status on the calendar event
 */
export default class CalendarEventStatusUpdate {
    constructor(status) {
        const selectField = status.querySelector('select');
        const form = selectField.parentNode;
        const message = form.parentNode.querySelector('.cal-event-user-status__message');

        status.querySelector('select').addEventListener('change', (event) => {
            this.statusChanged(
                message,
                selectField,
                form.action
            );
        });
    }

    statusChanged = (message, selectField, routeUrl) => {
        const errorBackgroundColor = 'rgba(255, 0, 0, 0.8)';
        const successBackgroundColor = 'rgba(0, 117, 0, 0.6)';

        const updateMessage = (text, error) => {
            message.innerText = text;
            error ? selectField.style.backgroundColor = errorBackgroundColor : selectField.style.backgroundColor = successBackgroundColor;

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
            })
            .catch(function (error) {
                console.log(error.response);

                if (error.response.data && error.response.data.message) {
                    updateMessage(error.response.data.message, true);
                    return;
                }

                updateMessage('Ooops! Error!', true);
            });
    }
}
