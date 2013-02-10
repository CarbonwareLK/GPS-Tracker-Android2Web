package org.gpstracker.mobile.android;

import java.io.BufferedReader;
import java.io.IOException;

import org.gpstracker.mobile.cont.LocationListnerHandler;
import org.gpstracker.mobile.cont.SettingsHandler;
import org.gpstracker.mobile.res.Observers;
import org.gpstracker.mobile.res.TimeRangeState;

import android.location.Location;
import android.os.Bundle;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

public class MainActivity extends Activity implements Observers{

	private Button btSave;
	private Button btReset;
	private EditText etNumber;
	private Spinner spTimeRange;
	//
	private SettingsHandler settingsHandler;
	private LocationListnerHandler locationListnerHandler;
	private static String TAG = MainActivity.class.getCanonicalName();

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		//
		settingsHandler = new SettingsHandler();
		locationListnerHandler = LocationListnerHandler.getInstance();

		btSave = (Button) findViewById(R.id.btSave);
		btReset = (Button) findViewById(R.id.btReset);

		etNumber = (EditText) findViewById(R.id.etNum);

		spTimeRange = (Spinner) findViewById(R.id.spTimeRange);

		ArrayAdapter<TimeRangeState> spinnerArrayAdapter = new ArrayAdapter(
				this, R.layout.custom_spiner, TimeRangeState.TimeRangeBuilder());

		// Step 3: Tell the spinner about our adapter
		spTimeRange.setAdapter(spinnerArrayAdapter);
		try {
			// TimeRangeState rangeState =
			// TimeRangeState.getRangeState(Integer.parseInt(settingsHandler.getValues("timerange",
			// "1000", MainActivity.this)));
			// Log.i(TAG, rangeState.getName()+"  "+rangeState.getId());
			int position = TimeRangeState.getRangeState(Integer
					.parseInt(settingsHandler.getValues("timerange", "1000",
							MainActivity.this)));

			StringBuilder stringBuilder = new StringBuilder();
			stringBuilder.append(position);
			stringBuilder.append("");
			Log.i(TAG, stringBuilder.toString());

			spTimeRange.setSelection(position, true);

			etNumber.setText(settingsHandler.getValues("number", "",
					MainActivity.this));

		} catch (NumberFormatException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (IOException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}

		btSave.setOnClickListener(new View.OnClickListener() {

			public void onClick(View v) {

				TimeRangeState rangeState = (TimeRangeState) spTimeRange
						.getSelectedItem();
				String number = etNumber.getEditableText().toString();

				try {

					boolean isWorking = locationListnerHandler.isRunning();
					if (isWorking) {
						locationListnerHandler.stopLocation();
					}

					settingsHandler.saveValues("number", number,
							MainActivity.this);
					settingsHandler.saveValues("timerange", rangeState.getId()
							+ "", MainActivity.this);

					Toast.makeText(MainActivity.this, "Settings Saved",
							Toast.LENGTH_LONG);

					AlertDialog alertDialog = new AlertDialog.Builder(
							MainActivity.this).create();

					alertDialog.setTitle("Settings");
					alertDialog.setMessage("Settings Saved");

					alertDialog.setButton("OK",
							new DialogInterface.OnClickListener() {
								public void onClick(DialogInterface dialog,
										int which) {
								}
							});
					alertDialog.show();

					if (isWorking) {
						locationListnerHandler.startLocation(MainActivity.this);
					}

				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

			}
		});

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.activity_main, menu);

		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle item selection
		switch (item.getItemId()) {
		case R.id.menu_settings:

			Intent intent = new Intent(MainActivity.this, TestGPS.class);
			MainActivity.this.startActivity(intent);

			return true;
		default:
			return super.onOptionsItemSelected(item);
		}
	}

	public void update(Location location) {
		Log.i(TAG, location.getAltitude()+"  "+ location.getLongitude());
		
	}

}
