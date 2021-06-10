package com.example.dentalapplication;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

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
        enteredAddress =findViewById(R.id.enteredAddress);
        enteredPassword = findViewById(R.id.enteredPassword);
        enteredRePassword = findViewById(R.id.enteredRePassword);
        register = findViewById(R.id.register);


register.setOnClickListener(new View.OnClickListener() {
    @Override
    public void onClick(View view) {
        registerUser();
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
        BackgroundTask backgroundTask = new BackgroundTask(this);
        backgroundTask.execute(method,userName,email,firstName,lastName,phoneNumber,address,password,rePassword);
        finish();

    }
}