package org.gpstracker.mobile.cont;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.gpstracker.mobile.res.Observers;

import android.content.Context;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.util.Log;

public class LocationListnerHandler {

	private static LocationListnerHandler handler;
	private static String TAG = LocationListnerHandler.class.getName();
	private LocationManager lm;
	private Mylocationlistener ll;
	private boolean isRunning = false;

	private Context context;

	private SettingsHandler settingsHandler;
	private Timer timer;
	private TimerTask task;

	private List<Observers> observerList = new ArrayList<Observers>();

	private LocationListnerHandler() {
		settingsHandler = new SettingsHandler();
	}

	public void addObservers(Observers observers) {

		observerList.add(observers);
	}

	public synchronized void startLocation(Context context)
			throws NumberFormatException, IOException {

		Log.i(TAG, "UPDATE");
		if (!isRunning) {
			
			
			if (lm == null) {
				lm = (LocationManager) context
						.getSystemService(Context.LOCATION_SERVICE);
			}
			
			
			if (ll == null) {
				ll = new Mylocationlistener();
			}
			
			
			this.context = context;
			lm.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, ll);
			timer = new Timer();
			int timetrange = 10000;
			timetrange = getTimeRange();
			task = new TimerTask() {

				@Override
				public void run() {
					for (Observers observers : observerList) {
						observers.update(ll.location);
					}
				}
			};
			timer.schedule(task, 100, timetrange);
			isRunning = true;
		}

	}

	private int getTimeRange() throws NumberFormatException, IOException {
		int timerange = Integer.parseInt(settingsHandler.getValues("timerange",
				"00", context));
		return timerange;
	}

	public synchronized void stopLocation() {
		lm.removeUpdates(ll);
		timer.cancel();
		isRunning = false;
	}

	private class Mylocationlistener implements LocationListener {

		final LocationListnerHandler listnerHandler = LocationListnerHandler
				.getInstance();

		private Location location;

		public void onLocationChanged(Location location) {

			if (location != null) {
				this.location = location;
				
			}
		}

		public void onProviderDisabled(String provider) {
		}

		public void onProviderEnabled(String provider) {
		}

		public void onStatusChanged(String provider, int status, Bundle extras) {
		}
	}

	public static synchronized LocationListnerHandler getInstance() {
		if (handler == null) {
			handler = new LocationListnerHandler();
		}
		return handler;
	}

	public boolean isRunning() {
		return isRunning;
	}

}
