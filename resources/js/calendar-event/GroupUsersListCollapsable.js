function GroupUsersListCollapsable() {
    document.addEventListener('DOMContentLoaded', function() {
        const collapsedSections = document.querySelectorAll('.calendar-event-group-users--collapsed');

        collapsedSections.forEach(section => {
            section.querySelector('.calendar-event-group-users__title').addEventListener('click', function() {
                section.classList.toggle('calendar-event-group-users--expanded');
            });
        });
    });
}

export default GroupUsersListCollapsable;
