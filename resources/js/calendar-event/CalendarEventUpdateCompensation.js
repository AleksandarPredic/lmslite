export default class CalendarEventUpdateCompensation {
    constructor(updateContainer) {
        const statusSelect = updateContainer.querySelector('select[name="status"]');
        const paymentSelect = updateContainer.querySelector('select[name="payment_completed"]');
        const form = statusSelect.closest('form');
        const message = updateContainer.querySelector('.cal-event-compensation__update-processing-message');
        const userItemParentElement = updateContainer.parentNode.parentNode;

        statusSelect.addEventListener('change', () => {
            this.fieldChanged(message, statusSelect, paymentSelect, form.action, userItemParentElement);
        });

        paymentSelect.addEventListener('change', () => {
            this.fieldChanged(message, statusSelect, paymentSelect, form.action, userItemParentElement);
        });
    }

    fieldChanged = (message, statusSelect, paymentSelect, routeUrl, userItemParentElement) => {
        const errorBackgroundColor = 'rgba(255, 0, 0, 0.8)';
        const successBackgroundColor = 'rgba(0, 117, 0, 0.6)';
        const successUserItemParentElementCssClass = 'singular-meta__item-user-attended';

        const updateMessage = (text, error) => {
            statusSelect.disabled = false;
            paymentSelect.disabled = false;
            message.innerText = text;
            if (error) {
                statusSelect.style.backgroundColor = errorBackgroundColor;
                paymentSelect.style.backgroundColor = errorBackgroundColor;
            } else {
                statusSelect.style.backgroundColor = successBackgroundColor;
                paymentSelect.style.backgroundColor = successBackgroundColor;
            }
            setTimeout(() => {
                if (!error) {
                    message.innerText = '';
                }
                statusSelect.style.backgroundColor = '';
                paymentSelect.style.backgroundColor = '';
            }, 3000);
        };

        message.innerText = 'saving...';
        statusSelect.disabled = true;
        paymentSelect.disabled = true;

        const data = {
            status: statusSelect.value,
            payment_completed: paymentSelect.value
        };
        const statusStartingValue = statusSelect.dataset.currentvalue;
        const paymentStartingValue = paymentSelect.dataset.currentvalue;

        axios.patch(routeUrl, data)
            .then(function (response) {
                statusSelect.dataset.currentvalue = statusSelect.value;
                paymentSelect.dataset.currentvalue = paymentSelect.value;
                updateMessage('Updated!', false);

                // Mark green if status is attended
                if (data.status === 'attended' && userItemParentElement) {
                    userItemParentElement.classList.add(successUserItemParentElementCssClass);
                } else if (userItemParentElement && userItemParentElement.classList.contains(successUserItemParentElementCssClass)) {
                    userItemParentElement.classList.remove(successUserItemParentElementCssClass);
                }
            })
            .catch(function (error) {
                statusSelect.value = statusStartingValue ? statusStartingValue : '';
                paymentSelect.value = paymentStartingValue ? paymentStartingValue : '';
                let errorMessage = 'Ooops! Error!';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }
                updateMessage(errorMessage, true);
            });
    }
}
