// WebsiteMonitor.js
class WebsiteMonitor {
    constructor(websites) {
        this.websites = websites;
        // JavaScript: Definition des Caches für Serverinformationen
        this.serverInfoCache = {};
    }

    init() {
        this.setupSortable();
        this.setupTooltips();
        this.initializeWebsites();
        this.setupToggleButtons();
        this.setupEventHandlers(); // Registrieren Sie alle Event-Handler
    }

    setupSortable() {
        $("#sortable-cards").sortable({
            handle: ".card-header",
            placeholder: "sortable-placeholder"
        });
    }

    setupTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    initializeWebsites() {
        this.websites.forEach((website) => {
            var urlHash = CryptoJS.MD5(website.url).toString();
            this.checkAvailability(website.url, urlHash);
            this.checkLoadTime(website.url, urlHash);
        });
    }

    setupToggleButtons() {
        var toggleButtons = document.querySelectorAll('.toggle-btn');
        toggleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                var icon = button.querySelector('i');
                if (button.getAttribute('aria-expanded') === 'true') {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });
    }

    setupEventHandlers() {
        const events = [
            { selector: '.server-info-btn', handler: this.showServerInfo, attributes: ['data-url', 'data-host', 'data-port', 'data-user', 'data-pass'] },
            { selector: '.check-loadtime-btn', handler: this.checkLoadTime, attributes: ['data-url', 'data-urlHash'] },
            { selector: '.check-availability-btn', handler: this.checkAvailability, attributes: ['data-url', 'data-urlHash'] },
            { selector: '.check-updates-btn', handler: this.checkUpdates, attributes: ['data-url', 'data-urlHash', 'data-user_api', 'data-pass_api', 'data-type'] },
            { selector: '.check-google-traffic-btn', handler: this.checkGoogleTraffic, attributes: ['data-url', 'data-urlHash', 'data-propertyId'] },
            { selector: '.check-comments-btn', handler: this.checkComments, attributes: ['data-url', 'data-urlHash', 'data-spamApi'] },
            { selector: '.check-seo-btn', handler: this.checkSEO, attributes: ['data-url', 'data-urlHash'] },
            { selector: '.check-security-btn', handler: this.checkSecurity, attributes: ['data-url', 'data-urlHash', 'data-host', 'data-port', 'data-user', 'data-pass', 'data-path'] },
            { selector: '.check-server-load-btn', handler: this.checkServerLoad, attributes: ['data-url', 'data-urlHash', 'data-host', 'data-port', 'data-user', 'data-pass'] },
            { selector: '.check-log-traffic-btn', handler: this.checkLogTraffic, attributes: ['data-url', 'data-urlHash'] }
        ];

        events.forEach(event => {
            document.querySelectorAll(event.selector).forEach(button => {
                button.addEventListener('click', () => {
                    const args = event.attributes.map(attr => button.getAttribute(attr));
                    event.handler.apply(this, args);
                });
            });
        });
    }


    showSpinner(elementId) {
        var element = document.getElementById(elementId);
        element.innerHTML = '<div class="spinner-grow spinner-grow-sm text-primary" role="status"><span class="visually-hidden">Lade Daten...</span></div>';
    }

    hideSpinner(elementId) {
        var element = document.getElementById(elementId);
        element.innerHTML = '';
    }

    showServerInfo(url, host, port, user, pass) {
        var modal = new bootstrap.Modal(document.getElementById('serverInfoModal'));
        var content = document.getElementById('server-info-content');
        var spinner = document.getElementById('server-info-spinner');

        // Überprüfen, ob Informationen bereits im Cache sind
        if (this.serverInfoCache[url]) { // this.serverInfoCache anstatt serverInfoCache
            content.innerHTML = this.serverInfoCache[url]; // this.serverInfoCache anstatt serverInfoCache
            modal.show();
            return;
        }

        content.innerHTML = ''; // Inhalt löschen
        this.showSpinner('server-info-content'); // Spinner anzeigen

        $.get('fetch/serverInfo.php', {
            url: url,
            host: host,
            port: port,
            user: user,
            pass: pass
        }, (response) => {
            this.hideSpinner('server-info-content');
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                content.innerHTML = `
                    <p><strong>IP Address:</strong> ${result['Server IP']}</p>
                    <p><strong>FCGI:</strong> ${result['FCGI']}</p>
                    <p><strong>Max Execution Time:</strong> ${result['Max Execution Time']}</p>
                    <p><strong>PHP Version:</strong> ${result['PHP Version']}</p>
                    <p><strong>DB Version:</strong> ${result['DB Version']}</p>
                    <p><strong>Safe Mode:</strong> ${result['Safe Mode']}</p>
                    <p><strong>Memory Limit:</strong> ${result['Memory Limit']}</p>
                    <p><strong>Memory Get Usage:</strong> ${result['Memory Get Usage']}</p>
                    <p><strong>Memory Peak Usage:</strong> ${result['Memory Peak Usage']}</p>
                    <p><strong>PDO Enabled:</strong> ${result['PDO Enabled']}</p>
                    <p><strong>Curl Enabled:</strong> ${result['Curl Enabled']}</p>
                    <p><strong>Zlib Enabled:</strong> ${result['Zlib Enabled']}</p>
                    <p><strong>Is Multisite:</strong> ${result['Is Multisite']}</p>
                `;
                content.innerHTML = infoHtml;

                // Informationen im Cache speichern
                this.serverInfoCache[url] = infoHtml; // this.serverInfoCache anstatt serverInfoCache
            } catch (e) {
                content.innerHTML = '<p>Error retrieving data.</p>';
            }
            modal.show(); // Modal anzeigen
        });
    }

    checkUpdates(url, urlHash, user_api, pass_api, type) {
        var statusListId = 'updates-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/updates.php', {
            mode: 'single',
            url: url,
            user_api: user_api,
            pass_api: pass_api,
            type: type
        }, (response) => {
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                this.hideSpinner(statusListId);
                $('#' + statusListId).html(result.data);
            } catch (e) {
                $('#' + statusListId).html('<p>Error retrieving data.</p>');
            }
        });
    }

    checkGoogleTraffic(url, urlHash, propertyId) {
        var statusListId = 'google-traffic-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/googleTraffic.php', {
            url: url,
            propertyId: propertyId
        }, (response) => {
            this.hideSpinner(statusListId);
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                if (result.data && result.data.length > 0) {
                    var output = '<table class="table"><thead><tr><th>Source/Medium</th><th>Campaign</th><th>Sessions</th></tr></thead><tbody>';
                    result.data.forEach(function (row) {
                        output += '<tr><td>' + row['dimension0'] + '</td><td>' + row['dimension1'] + '</td><td>' + row['metric0'] + '</td></tr>';
                    });
                    output += '</tbody></table>';
                    $('#' + statusListId).html(output);
                } else if (result.error) {
                    $('#' + statusListId).html('<p>Error: ' + result.message + '</p>');
                } else {
                    $('#' + statusListId).html('<p>No data available for the selected period.</p>');
                }
            } catch (e) {
                $('#' + statusListId).html('<p>Error retrieving data.</p>');
            }
        });
    }

    checkAvailability(url, urlHash) {
        var statusHeaderId = 'availability-status-header-' + urlHash;
        this.showSpinner(statusHeaderId);
        $.get('/check/availability.php', {
            url: url
        }, (response) => {
            this.hideSpinner(statusHeaderId);
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                var statusHeader = $('#' + statusHeaderId);
                statusHeader.empty();
                $.each(result, function (url, status) {
                    var icon;
                    if (status.includes("UP")) {
                        icon = '<i class="fas fa-check-circle text-success"></i>';
                    } else if (status.includes("403")) {
                        icon = '<i class="fas fa-times-circle text-warning"></i>'; // Gelb für Forbidden
                    } else {
                        icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für andere Fehler
                    }
                    statusHeader.html('&nbsp' + icon + ' ' + status);
                });
            } catch (e) {
                $('#' + statusHeaderId).html('<p>Error retrieving data.</p>');
            }
        });
    }

    checkLoadTime(url, urlHash) {
        var statusHeaderId = 'loadtime-status-header-' + urlHash;
        this.showSpinner(statusHeaderId);
        $.get('/check/loadtime.php', {
            url: url
        }, (response) => {
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                this.hideSpinner(statusHeaderId);
                var statusHeader = $('#' + statusHeaderId);
                statusHeader.empty();
                $.each(result, function (url, time) {
                    var icon;
                    if (time < 2) {
                        icon = '<i class="fas fa-check-circle text-success"></i>'; // Grün für gute Ladezeit
                    } else if (time >= 2 && time <= 4) {
                        icon = '<i class="fas fa-exclamation-circle text-warning"></i>'; // Gelb für akzeptable Ladezeit
                    } else {
                        icon = '<i class="fas fa-times-circle text-danger"></i>'; // Rot für schlechte Ladezeit
                    }
                    statusHeader.html('&nbsp' + icon + ' ' + time.toFixed(2) + 's ');
                });
            } catch (e) {
                $('#' + statusHeaderId).html('<p>Error retrieving data.</p>');
            }
        });
    }

    checkComments(url, urlHash, spamApi) {
        var statusListId = 'comments-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('fetch/comments.php', {
            url: url
        }, (response) => {
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                this.hideSpinner(statusListId);
                var statusList = $('#' + statusListId);
                statusList.empty();
                $.each(result, function (index, comment) {
                    var commentData = {
                        permalink: comment.link,
                        comment_type: 'comment',
                        comment_author: comment.author_name,
                        comment_author_email: comment.author_email,
                        comment_author_url: comment.author_url,
                        comment_content: comment.content.rendered
                    };
                    $.post('/check/comment.php', {
                        spam_api: spamApi,
                        blog_url: url,
                        comment: commentData
                    }, function (data) {
                        var icon;
                        if (data == 'true') {
                            icon = '<i class="fas fa-times-circle text-danger"></i> Spam';
                        } else {
                            icon = '<i class="fas fa-check-circle text-success"></i> Kein Spam';
                        }
                        statusList.append('<div>' + commentData.comment_author + ': ' + icon + '</div>');
                    });
                });
            } catch (e) {
                $('#' + statusListId).html('<p>Error retrieving data.</p>');
            }
        });
    }

    checkSEO(url, urlHash) {
        var statusListId = 'seo-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/seo.php', {
            url: url
        }, (response) => {
            var result = typeof response === 'string' ? JSON.parse(response) : response;
            this.hideSpinner(statusListId);
            var statusList = $('#' + statusListId);
            statusList.empty();
            statusList.append('<div><strong>Titel:</strong> ' + result.title + '</div>');
            statusList.append('<div><strong>Beschreibung:</strong> ' + result.description + '</div>');
            statusList.append('<div><strong>Sitemap:</strong> <a href="' + result.sitemap + '">' + result.sitemap + '</a></div>');
            statusList.append('<div><strong>Robots.txt:</strong> <pre>' + result.robots + '</pre></div>');
        });
    }

    checkSecurity(url, host, port, user, pass, path, urlHash) {
        var statusListId = 'security-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/security.php', {
            url: url,
            host: host,
            port: port,
            user: user,
            pass: pass,
            path: path
        }, (response) => {
            var result = typeof response === 'string' ? JSON.parse(response) : response;
            this.hideSpinner(statusListId);
            $('#' + statusListId).html(result.data);
        });
    }

    checkServerLoad(url, urlHash, host, port, user, pass) {
        var statusListId = 'server-load-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/serverLoad.php', {
            url: url,
            host: host,
            port: port,
            user: user,
            pass: pass,
        }, (data) => {
            this.hideSpinner(statusListId);
            var result = JSON.parse(data);
            $('#' + statusListId).html('Serverauslastung: ' + result.load + '%');
        });
    }

    checkLogTraffic(url, urlHash) {
        var statusListId = 'log-traffic-status-' + urlHash;
        this.showSpinner(statusListId);
        $.get('/check/logTraffic.php', {
            url: url,
        }, (data) => {
            this.hideSpinner(statusListId);
            var result = JSON.parse(data);
            $('#' + statusListId).html('Besucher von Google: ' + result.log_traffic);
        });
    }
}