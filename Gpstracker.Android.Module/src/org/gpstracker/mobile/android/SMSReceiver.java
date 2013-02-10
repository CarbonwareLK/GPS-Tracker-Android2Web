package org.gpstracker.mobile.android;

import java.io.IOException;
import java.net.URI;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.gpstracker.mobile.cont.LocationListnerHandler;
import org.gpstracker.mobile.cont.RestHelper;
import org.gpstracker.mobile.cont.SettingsHandler;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.telephony.gsm.SmsMessage;
import android.util.Log;
import android.widget.Toast;

public class SMSReceiver extends BroadcastReceiver {
	private static final String TAG = "Message recieved";

	private Context context;
	//
	private SettingsHandler settingsHandler;

	
	
	
	private LocationListnerHandler handler=LocationListnerHandler.getInstance();

	@Override
	public void onReceive(Context context, Intent intent) {

		settingsHandler = new SettingsHandler();

		this.context = context;

		try {
			String number = settingsHandler.getValues("number", "00", context);
			
			
			Log.v("TAGF", "Sms Recived");
			
			Bundle pudsBundle = intent.getExtras();
			Object[] pdus = (Object[]) pudsBundle.get("pdus");
			
			
			SmsMessage messages = SmsMessage.createFromPdu((byte[]) pdus[0]);
			
			
			
			
			Log.i(TAG, messages.getMessageBody());

			String sender = messages.getOriginatingAddress();

			Log.i(TAG, sender + "  " + number);

			if (sender.endsWith(number)) {

				Toast.makeText(
						context,
						"SMS Received : " + messages.getMessageBody() + "  "
								+ sender, Toast.LENGTH_LONG).show();

				handler.startLocation(context);
				handler.addObservers(RestHelper.getRestHelper());
			}

		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	

	

}
