package org.gpstracker.mobile.cont;

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.Serializable;
import java.util.Calendar;
import java.util.Map;
import java.util.Properties;

import android.app.Activity;
import android.content.Context;

public class SettingsHandler implements Serializable {

	private static final String FILENAME = "gps_vehicle_traking.properties";
	private static final String tag = SettingsHandler.class.getName();
	private final Properties properties = new Properties();
	private Context context;

	/**
	 * 
	 * Use this for save values
	 * 
	 * 
	 * @param key
	 * @param value
	 * @throws IOException
	 */
	public void saveValues(String key, String value, Context context)
			throws IOException {
		this.context = context;
		FileOutputStream outputStream = context.openFileOutput(FILENAME,
				Context.MODE_APPEND);
		properties.setProperty(key, value);
		properties.store(outputStream, Calendar.getInstance().getTime()
				.toString());

	}

	/**
	 * Use this for get saved values
	 * 
	 * @param key
	 * @return
	 * @throws IOException
	 */
	public String getValues(String key, String defaultValue, Context context)
			throws IOException {
		this.context = context;
		String value = "0";

		FileInputStream fileInputStream = context.openFileInput(FILENAME);
		properties.load(fileInputStream);
		value = properties.getProperty(key, defaultValue);

		return value;
	}

}
