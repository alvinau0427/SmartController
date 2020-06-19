//package com.iot.digitalhome.Util;
//
//import android.app.NotificationManager;
//import android.app.PendingIntent;
//import android.content.Context;
//import android.content.Intent;
//import android.graphics.BitmapFactory;
//import android.media.RingtoneManager;
//import android.net.Uri;
//import android.support.v4.app.NotificationCompat;
//import android.util.Log;
//
//import com.google.firebase.messaging.FirebaseMessagingService;
//import com.google.firebase.messaging.RemoteMessage;
//import com.iot.digitalhome.Activity.MainActivity;
//import com.iot.digitalhome.R;
//
//import org.json.JSONException;
//import org.json.JSONObject;
//
//public class MyFcmListenerService extends FirebaseMessagingService {
//
//    @Override
//    public void onMessageReceived(RemoteMessage remoteMessage){
//        sendNotification(remoteMessage);
//    }
//
//    private void sendNotification(RemoteMessage remoteMessage) {
//        try {
//            String messagebody = remoteMessage.getNotification().getBody();
//
//            JSONObject data = new JSONObject(messagebody);
//
//            Intent intent = new Intent(this, MainActivity.class);
//            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//            PendingIntent pendingIntent = PendingIntent.getActivity(this, 0 , intent, PendingIntent.FLAG_UPDATE_CURRENT);
//
//            Uri defaultSoundUri= RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
//
//            NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
//                    .setSmallIcon(R.drawable.img_digitalhome_logo)
//                    .setLargeIcon(BitmapFactory.decodeResource(getResources(), R.drawable.img_digitalhome_logo))
//                    .setContentText(data.getString("regulationHeader") + "\n" + "Do you want to " + data.getString("toStatusDescription") + " the " + data.getString("actuatorName"))
//                    .setAutoCancel(true)
//                    .setSound(defaultSoundUri)
//                    .setContentIntent(pendingIntent);
//
////            notificationBuilder.addAction(R.drawable.common_full_open_on_phone, data.getString("toStatusDescription"), PendingIntent.getActivity(this, data.getInt("actuatorID"), new Intent(this, MainActivity.class), 0));
////
////            if (data.getString("toStatusDescription") == "Close") {
////                notificationBuilder.addAction(R.drawable.common_full_open_on_phone, "Open", PendingIntent.getActivity(this, 0, new Intent(this, MainActivity.class), 0));
////            } else {
////                notificationBuilder.addAction(R.drawable.common_full_open_on_phone, "Close", PendingIntent.getActivity(this, 0, new Intent(this, MainActivity.class), 0));
////            }
//
//            NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
//
//            notificationManager.notify(0 , notificationBuilder.build());
//        } catch (JSONException e) {
//            e.printStackTrace();
//        }
//    }
//}
