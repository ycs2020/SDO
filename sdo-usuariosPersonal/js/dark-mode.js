// dark-mode.js
document.addEventListener('DOMContentLoaded', () => {
    let darkMode = localStorage.getItem('darkMode') || 'off';
    let switchToggle = document.querySelector('#customSwitch3');

    if (darkMode === 'on') {
        document.body.classList.add('dark-mode');
        if (switchToggle) switchToggle.checked = true;
    }

    if (switchToggle) {
        switchToggle.addEventListener('change', () => {
            const isDark = switchToggle.checked;
            document.body.classList.toggle('dark-mode', isDark);
            localStorage.setItem('darkMode', isDark ? 'on' : 'off');
        });
    }
});