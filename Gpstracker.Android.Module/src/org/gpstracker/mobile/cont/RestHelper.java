package org.gpstracker.mobile.cont;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.gpstracker.mobile.res.Observers;

import android.location.Location;
import android.util.Log;

public class RestHelper implements Observers {

	private static final String URL = "http://dewmal.site90.net/rest.php";
	private static final String URL_Login = "http://dewmal.site90.net/rest_login.php";
	private static double preAtti;
	private static double preLong;

	private static final RestHelper REST_HELPER = new RestHelper();
	private static String User = "";

	private RestHelper() {

	}

	public static synchronized RestHelper getRestHelper() {
		return REST_HELPER;
	}

	// Overrided Observer
	public void update(Location location) {
		synchronized (location) {
			try {
				connect(location);
			} catch (ClientProtocolException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}

	}

	public void connect(Location location) throws ClientProtocolException,
			IOException {
		double latitude = location.getLatitude();
		double longitude = location.getLongitude();
		if (preAtti != latitude || preLong != longitude) {
			preAtti = latitude;
			preLong = longitude;
			HttpClient httpClient = new DefaultHttpClient();
			HttpPost httpPost = new HttpPost(
					"http://dewmal.site90.net/rest.php");
			List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
			// Adding parameters to send to the HTTP server.
			DecimalFormat format = new DecimalFormat("#.#####");
			nameValuePairs.add(new BasicNameValuePair("atti", format
					.format(preAtti) + ""));
			nameValuePairs.add(new BasicNameValuePair("lon", format
					.format(preLong) + ""));
			nameValuePairs.add(new BasicNameValuePair("user", User));
			httpPost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
			httpPost.setHeader("Content-Type",
					"application/x-www-form-urlencoded");
			HttpResponse httpResponse = httpClient.execute(httpPost);
			Log.i("LOCATION",
					getASCIIContentFromEntity(httpResponse.getEntity()));
		}

	}

	public void getUser(String email, String password)
			throws ClientProtocolException, IOException {

		HttpClient httpClient = new DefaultHttpClient();
		HttpPost httpPost = new HttpPost(URL_Login);

		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();

		// Adding parameters to send to the HTTP server.

		nameValuePairs.add(new BasicNameValuePair("email", email));
		nameValuePairs.add(new BasicNameValuePair("password", password));

		httpPost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

		HttpResponse response = httpClient.execute(httpPost);

		HttpEntity entity = response.getEntity();

		String text = getASCIIContentFromEntity(entity);

		User = text;

	}

	protected String getASCIIContentFromEntity(HttpEntity entity)
			throws IllegalStateException, IOException {

		InputStream is = entity.getContent();
		/*
		 * To convert the InputStream to String we use the
		 * BufferedReader.readLine() method. We iterate until the BufferedReader
		 * return null which means there's no more data to read. Each line will
		 * appended to a StringBuilder and returned as String.
		 */
		BufferedReader reader = new BufferedReader(new InputStreamReader(is));
		StringBuilder sb = new StringBuilder();

		String line = null;
		try {
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
			}
		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			try {
				is.close();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		return sb.toString();
	}

}
