package com.example.dentalapplication;

import androidx.appcompat.app.AppCompatActivity;
import androidx.loader.content.AsyncTaskLoader;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import android.os.AsyncTask;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.lang.reflect.Array;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.EventListener;
import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {

    private EditText email;
    private EditText password;
    private Button login;
    private Button registration;
    private String jsonEmail;
    private String jsonPassword;
    private String json_url = "https://dentalappibu.000webhostapp.com/getEmailAndPassword.php";


    public HashMap<String, String> map;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        email = findViewById(R.id.email);
        password = findViewById(R.id.password);
        login = findViewById(R.id.login);
        registration = findViewById(R.id.registration);
        GetData getData = new GetData();
        getData.execute();
        map = new HashMap<>();
        setTitle("Login");
        String intentEmail = getIntent().getStringExtra("email");
        String intentPassword = getIntent().getStringExtra("password");
        map.put(intentEmail,intentPassword);

        registration.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(LoginActivity.this, RegistrationActivity.class);
                startActivity(intent);
            }
        });

        login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {



//                for (Map.Entry<String, String> entry : map.entrySet()) {
//                    if(entry.getKey().equals(email.getText().toString().trim()) && entry.getValue().equals(password.getText().toString().trim())){
//                        Toast.makeText(LoginActivity.this, "Welcome 1", Toast.LENGTH_SHORT).show();
//                        Intent intent = new Intent(LoginActivity.this, MainActivity.class);
//                        startActivity(intent);
//                    }
//                    else{
//                        Toast.makeText(LoginActivity.this, "Wrong username or password", Toast.LENGTH_SHORT).show();
//                    }
//                }

              if(checkIfFieldsAreEmpty()){
                  Toast.makeText(LoginActivity.this, "Please fill all the fields", Toast.LENGTH_SHORT).show();
              }
              else {

                  if (map.containsKey(email.getText().toString().trim()) && map.get(email.getText().toString().trim()).equals(password.getText().toString().trim())){

                      if (email.getText().toString().trim().equals("mahadib124@gmail.com") || email.getText().toString().trim().equals("edvincama00@gmail.com")){
                          Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                          startActivity(intent);
                          Toast.makeText(LoginActivity.this, "Welcome Admin", Toast.LENGTH_SHORT).show();
                      }
                      else{
                          Intent intent = new Intent(LoginActivity.this, UserActivity.class);
                          startActivity(intent);
                          Toast.makeText(LoginActivity.this, "Welcome User", Toast.LENGTH_SHORT).show();
                      }

                  }
                  else{
                      Toast.makeText(LoginActivity.this, "Wrong username or password", Toast.LENGTH_SHORT).show();
                  }
              }


            }
        });


    }

    @SuppressLint("StaticFieldLeak")
    public class GetData extends AsyncTask<String, String, String> {


        @Override
        protected String doInBackground(String... strings) {
            String current = "";

            try {
                URL url;
                HttpURLConnection urlConnection = null;


                try {
                    url = new URL(json_url);
                    urlConnection = (HttpURLConnection) url.openConnection();
                    InputStream in = urlConnection.getInputStream();
                    InputStreamReader isr = new InputStreamReader(in);

                    int data = isr.read();
                    while (data != -1) {

                        current += (char) data;
                        data = isr.read();

                    }
                    return current;

                } catch (MalformedURLException e) {
                    e.printStackTrace();
                } catch (IOException e) {
                    e.printStackTrace();
                } finally {
                    if (urlConnection != null) {
                        urlConnection.disconnect();
                    }
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
            return current;

        }


        @Override
        protected void onPostExecute(String s) {
            try {
                JSONObject jsonObject = new JSONObject(s);
                JSONArray jsonArray = jsonObject.getJSONArray("server_response");

                for (int i = 0; i < jsonArray.length(); i++) {


                    JSONObject jsonObject1 = jsonArray.getJSONObject(i);
                    jsonEmail = jsonObject1.getString("Email");
                    jsonPassword = jsonObject1.getString("password");
                    map.put(jsonEmail, jsonPassword);

                }
            } catch (JSONException e) {
                e.printStackTrace();
            }


        }
    }

    private boolean checkIfFieldsAreEmpty() {
if (TextUtils.isEmpty(email.getText()) || TextUtils.isEmpty(password.getText())){
    return true;
}
else{
    return false;
}
    }


}
