
document.addEventListener("DOMContentLoaded", function() {
    var ajax_url = checkemail_pushdata.ajax_url
    var ck_mail_security_nonce = checkemail_pushdata.ck_mail_security_nonce
    var firebaseConfig = checkemail_pushdata.fcm_config;

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();



    document.addEventListener("push", (event) => {
        console.log('payload push')
        unreadCount += 1;
        // Set or clear the badge.
        if (navigator.setAppBadge) {
            if (unreadCount && unreadCount > 0) {
                navigator.setAppBadge(unreadCount);
            } else {
                navigator.clearAppBadge();
            }
        }
    });

    


    function requestPermission() {
        Notification.requestPermission().then(permission => {
            console.log(permission)
            if (permission === "granted") {
                messaging.getToken({ vapidKey: "AIzaSyDhRbFy9m-NXZVkozYJwKdDYJuwsL6W_bw" }).then(token => {
                    // console.log("Admin FCM Token:", token);
                    saveAdminToken(token);
                });
            }
        });
    }


    function saveAdminToken(token) {
        fetch(ajax_url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=checkmail_save_admin_fcm_token&token=" + token+"&ck_mail_security_nonce="+ck_mail_security_nonce
        }).then(response => response.json()).then(data => {
            console.log("Token Saved:");
        });
    }
    document.addEventListener("DOMContentLoaded", requestPermission);

    messaging.onMessage((payload) => {
        console.log("Message received. ", payload);
        new Notification(payload.notification.title, {
            body: payload.notification.body,
            icon: payload.notification.icon
        });
    });
})