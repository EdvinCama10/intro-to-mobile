package com.example.dentalapplication;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class RegistrationActivity extends AppCompatActivity {

    private EditText enteredUserName;
    private EditText enteredEmail;
    private EditText enteredFirstName;
    private EditText enteredLastName;
    private EditText enteredPhoneNumber;
    private EditText enteredAddress;
    private EditText enteredPassword;
    private EditText enteredRePassword;
    private Button register;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registration);

        enteredUserName = findViewById(R.id.enteredUserName);
        enteredEmail = findViewById(R.id.enteredEmail);
        enteredFirstName = findViewById(R.id.enteredFirstName);
        enteredLastName = findViewById(R.id.enteredLastname);
        enteredPhoneNumber = findViewById(R.id.enteredPhoneNumber);
        enteredAddress = findViewById(R.id.enteredAddress);
        enteredPassword = findViewById(R.id.enteredPassword);
        enteredRePassword = findViewById(R.id.enteredRePassword);
        register = findViewById(R.id.register);


        register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (checkIfFieldsAreEmpty()) {

                    Toast.makeText(RegistrationActivity.this, "Please fill all the fields", Toast.LENGTH_SHORT).show();

                } else {
                    if (checkIfPasswordsMatch()) {
                        registerUser();
                        Intent intent = new Intent(RegistrationActivity.this, LoginActivity.class);
                        intent.putExtra("email",enteredEmail.getText().toString().trim());
                        intent.putExtra("password",enteredPassword.getText().toString().trim());
                        startActivity(intent);
                    } else {
                        Toast.makeText(RegistrationActivity.this, "Passwords Don't match !", Toast.LENGTH_SHORT).show();
                    }
                }

            }

        });


    }

    private void registerUser() {

        String userName = enteredUserName.getText().toString().trim();
        String email = enteredEmail.getText().toString().trim();
        String firstName = enteredFirstName.getText().toString().trim();
        String lastName = enteredLastName.getText().toString().trim();
        String phoneNumber = enteredPhoneNumber.getText().toString();
        String address = enteredAddress.getText().toString().trim();
        String password = enteredPassword.getText().toString().trim();
        String rePassword = enteredRePassword.getText().toString().trim();


        String method = "register";
        BackgroundTaskRegistration backgroundTaskRegistration = new BackgroundTaskRegistration(this);
        backgroundTaskRegistration.execute(method, userName, email, firstName, lastName, phoneNumber, address, password, rePassword);
        finish();

    }

    private boolean checkIfFieldsAreEmpty() {
        if (TextUtils.isEmpty(enteredUserName.getText()) ||
                TextUtils.isEmpty(enteredEmail.getText()) ||
                TextUtils.isEmpty(enteredFirstName.getText()) ||
                TextUtils.isEmpty(enteredLastName.getText()) ||
                TextUtils.isEmpty(enteredPhoneNumber.getText()) ||
                TextUtils.isEmpty(enteredAddress.getText()) ||
                TextUtils.isEmpty(enteredPassword.getText()) ||
                TextUtils.isEmpty(enteredRePassword.getText())) {

            return true;
        }

        return false;


    }

    private boolean checkIfPasswordsMatch() {
        if (enteredPassword.getText().toString().trim().equals(enteredRePassword.getText().toString().trim())) {
            return true;
        }
        return false;
    }


}