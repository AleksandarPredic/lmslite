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
            selectField.disabled = false;

            message.innerText = text;
            if (error) {
                selectField.style.backgroundColor = errorBackgroundColor;
            } else {
                selectField.style.backgroundColor = successBackgroundColor;
            }

            setTimeout(() => {
                // Leave the error text until it successfully update value
                if (! error) {
                    message.innerText = '';
                }

                selectField.style.backgroundColor = '';
            }, 3000);
        }
        message.innerText = 'saving...';

        selectField.disabled = true;

        const data = {};
        data[selectField.name] = selectField.value;
        const selectFieldStartingValue = selectField.dataset.currentvalue;
        console.log(selectField, selectFieldStartingValue);

        axios.patch(routeUrl, data)
            .then(function (response) {

                // Set HTML attribute for fallback to the new value
                selectField.dataset.currentvalue = selectField.value;
                updateMessage(response.data.message, false);

                // After successfully updated, if the updated status is attended, mark the whole user meta section green
                if (data.hasOwnProperty('status')) {
                    // Mark green and remove green only for status changes, we don't care about info
                    if (data.status === 'attended') {
                        userItemParentElement.classList.add(successUserItemParentElementCssClass);
                    } else {
                        if (userItemParentElement.classList.contains(successUserItemParentElementCssClass)) {
                            userItemParentElement.classList.remove(successUserItemParentElementCssClass);
                        }
                    }

                    if (data.status === 'none') {
                        const infoStatus = userItemParentElement.querySelector('select[name="info"]');
                        // set value to none and Trigger change in vanilla js on infoStatus
                        if (infoStatus) {
                            infoStatus.value = 'none';
                            infoStatus.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }
            })
            .catch(function (error) {
                console.log(error.response);

                // Set HTML attribute for fallback to the previous value
                selectField.value = selectFieldStartingValue ? selectFieldStartingValue : 'none';

                if (error.response && error.response.data && error.response.data.message) {
                    const validationErrors = error.response.data.errors;
                    const errorMessage = validationErrors.status ? validationErrors.status[0] : error.response.data.message;
                    updateMessage(errorMessage, true);
                    return;
                }

                updateMessage('Ooops! Error!', true);
            });
    }
}
