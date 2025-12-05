package com.example.books;

import android.content.Intent;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class LoginActivity extends AppCompatActivity {

    private EditText emailEditText;
    private EditText passwordEditText;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_login);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        setTitle("Login");
        emailEditText = findViewById(R.id.editTextEmail);
        passwordEditText = findViewById(R.id.editTextPassword);
    }

    // Função para validar Email
    private boolean isEmailValido(String email) {
        return email != null && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }

    // Função para validar Password
    private boolean isPasswordValid(String password) {
        return password != null && password.length() >= 4;
    }

    public void onClickLogin(View view) {
        String email = emailEditText.getText().toString().trim();
        String password = passwordEditText.getText().toString();

        if (!isEmailValido(email)) {
            //msgens de erro
            emailEditText.setError("Email inválido");
            Toast.makeText(this, "Email inválido!", Toast.LENGTH_SHORT).show();
        }

        // Validação da password
        if (!isPasswordValid(password)) {
            //msgens de erro
            passwordEditText.setError("Password inválida");
            Toast.makeText(this, "Password inválida! (mínimo 6 caracteres)", Toast.LENGTH_SHORT).show();
        }

        if(isPasswordValid(password) && isEmailValido(email)) {
            Intent intent = new Intent(LoginActivity.this, MenuMainActivity.class);
            intent.putExtra("EMAIL", email);
            startActivity(intent);
            finish();
        }
    }
}