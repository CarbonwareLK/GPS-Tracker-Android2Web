package org.gpstracker.mobile.android;

import java.io.IOException;

import org.apache.http.client.ClientProtocolException;
import org.gpstracker.mobile.cont.RestHelper;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class Login extends Activity {

	private Button btLogin;
	private EditText etEmail;
	private EditText etPassw;	
	//
	private RestHelper restHelper=RestHelper.getRestHelper();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.login);
		
		btLogin=(Button) findViewById(R.id.login_view_bt_login);
		etEmail=(EditText) findViewById(R.id.login_username_et);
		etPassw=(EditText) findViewById(R.id.login_password_et);
		btLogin.setOnClickListener(new View.OnClickListener() {
			
			public void onClick(View arg0) {
			
				
				try {
					//restHelper.getUser(etEmail.getEditableText().toString(), etPassw.getText().toString());
					restHelper.getUser("sudeshanieranthika@gmail.com", "dew");
				} catch (ClientProtocolException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
				Intent intent=new Intent(Login.this, MainActivity.class);
				Login.this.startActivity(intent);
				Login.this.finish();
				
			}
		});
	}

	
	
}
