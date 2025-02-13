

importScripts("https://www.gstatic.com/firebasejs/7.8.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/7.8.2/firebase-analytics.js");
importScripts("https://www.gstatic.com/firebasejs/7.8.2/firebase-messaging.js");

const firebaseConfig = {
    apiKey: "AIzaSyDhRbFy9m-NXZVkozYJwKdDYJuwsL6W_bw",
    authDomain: "pushnotificationsio.firebaseapp.com",
    projectId: "pushnotificationsio",
    messagingSenderId: "788493704860",
    appId: "1:788493704860:web:ba71fd692e7cc9651f5759"
};


if (!firebase.apps.length) {firebase.initializeApp(firebaseConfig);}
const messaging = firebase.messaging();
var messageCount = 0;
var unreadCount = 0;

messaging.setBackgroundMessageHandler(function(payload) {
	console.log(payload)
const notificationTitle = payload.data.title;
const notificationOptions = {
				body: payload.data.body,
				icon: payload.data.icon,
				image: payload.data.image,
				vibrate: [100, 50, 100],
				data: {
					dateOfArrival: Date.now(),
					primarykey: payload.data.currentCampaign,
					url : payload.data.url
				  },
				}
				messageCount += 1;
				setBadge(messageCount);

	return self.registration.showNotification(notificationTitle, notificationOptions);
	

});

// messaging.onMessage((payload) => {
// 	console.log("Message received. ", payload);
// 	new Notification(payload.notification.title, {
// 		body: payload.notification.body,
// 		icon: payload.notification.icon
// 	});
// });


self.addEventListener("notificationclose", function(e) {
var notification = e.notification;
var primarykey = notification.data.primarykey;
	messageCount -= 1;
	unreadCount -= 1;
	if(messageCount>0 && unreadCount > 0){
		setBadge(messageCount);
	}else{
		clearBadge();
	}
	console.log("Closed notification: " + primarykey);
});

self.addEventListener("notificationclick", function(e) {
var notification = e.notification;
messageCount -= 1;
unreadCount -= 1;



notification.close(); // Close the notification

console.log("Clicked notification: " + notification.data);
});  




function setBadge(...args) {
  if (navigator.setAppBadge) {
    navigator.setAppBadge(...args);
  } else if (navigator.setExperimentalAppBadge) {
    navigator.setExperimentalAppBadge(...args);
  } else if (window.ExperimentalBadge) {
    window.ExperimentalBadge.set(...args);
  }
}

// Wrapper to support first and second origin trial
// See https://web.dev/badging-api/ for details.
function clearBadge() {
  if (navigator.clearAppBadge) {
    navigator.clearAppBadge();
  } else if (navigator.clearExperimentalAppBadge) {
    navigator.clearExperimentalAppBadge();
  } else if (window.ExperimentalBadge) {
    window.ExperimentalBadge.clear();
  }
}

self.addEventListener("push", (event) => {
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