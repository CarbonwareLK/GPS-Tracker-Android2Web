package org.gpstracker.mobile.android;

import java.io.IOException;

import org.gpstracker.mobile.cont.LocationListnerHandler;
import org.gpstracker.mobile.cont.RestHelper;
import org.gpstracker.mobile.cont.SettingsHandler;
import org.gpstracker.mobile.res.Observers;

import android.app.Activity;
import android.location.Location;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.TextView;

public class TestGPS extends Activity implements Observers {

	private TextView etLattitude;
	private TextView etLongitude;
	//
	private Button btStart;
	private Button btStop;

	private LocationListnerHandler locationListnerHandler;
	private static String TAG = TestGPS.class.getCanonicalName();

	public TestGPS() {

		locationListnerHandler = LocationListnerHandler.getInstance();
		locationListnerHandler.addObservers(TestGPS.this);
		locationListnerHandler.addObservers(RestHelper.getRestHelper());
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.test_gps);

		btStart = (Button) findViewById(R.id.btStart);
		btStop = (Button) findViewById(R.id.btStop);
		etLattitude = (TextView) findViewById(R.id.etLatt);
		etLongitude = (TextView) findViewById(R.id.etLongi);

		btStart.setOnClickListener(new View.OnClickListener() {

			public void onClick(View v) {

				try {
					locationListnerHandler.startLocation(TestGPS.this);
				} catch (NumberFormatException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

			}
		});

		btStop.setOnClickListener(new View.OnClickListener() {

			public void onClick(View v) {
				locationListnerHandler.stopLocation();

			}
		});
	}

	public void update(Location location) {
		synchronized (location) {

			Log.i(TAG, location.getLongitude() + "  " + location.getLongitude());
			etLattitude.setText(location.getLatitude() + "");
			etLongitude.setText(location.getLongitude() + "");
		}
	}

}
