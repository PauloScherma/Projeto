package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

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

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail;
    private EditText etPassword;



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
        setTitle("Title");
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);

    }

    // validate if Email format is valid
    private boolean isEmailValid(String email){
        return email != null && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }
    // validate if Password format is valid
    private boolean isPasswordValid(String password){
        return password != null && password.length() >= 4;
    }
    public void onClickLogin(View view) {
        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString();

        if (!isEmailValid(email)) {
            //msgens de erro
            etEmail.setError("Email inválido");
            Toast.makeText(this, "Email inválido!", Toast.LENGTH_SHORT).show();
        }

        // Validação da password
        if (!isPasswordValid(password)) {
            //msgens de erro
            etPassword.setError("Password inválida");
            Toast.makeText(this, "Password inválida! (mínimo 6 caracteres)", Toast.LENGTH_SHORT).show();
        }

        if(isPasswordValid(password) && isEmailValid(email)) {
            Intent intent = new Intent(LoginActivity.this, MenuMainActivity.class);
            intent.putExtra("EMAIL", email);
            startActivity(intent);
            finish();
        }
    }


}