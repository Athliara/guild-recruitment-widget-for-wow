(function () {
    'use strict';

    function setupTabs(root) {
        var tabs = root.querySelectorAll('.athlios-wrz-admin-tab');
        var panels = root.querySelectorAll('.athlios-wrz-admin-panel');

        Array.prototype.forEach.call(tabs, function (tab) {
            tab.addEventListener('click', function () {
                var panelId = tab.getAttribute('data-tab');

                Array.prototype.forEach.call(tabs, function (item) {
                    item.classList.toggle('is-active', item === tab);
                });

                Array.prototype.forEach.call(panels, function (panel) {
                    var matches = panel.getAttribute('data-panel') === panelId;
                    panel.classList.toggle('is-active', matches);
                    panel.hidden = !matches;
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('.athlios-wrz-admin-page');

        if (!root) {
            return;
        }

        setupTabs(root);
    });
})();
