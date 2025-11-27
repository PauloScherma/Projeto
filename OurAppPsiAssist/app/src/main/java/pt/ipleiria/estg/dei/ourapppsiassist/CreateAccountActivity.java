package pt.ipleiria.estg.dei.ourapppsiassist;

import android.os.Bundle;
import android.util.Patterns;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class CreateAccountActivity extends AppCompatActivity {

    private EditText etUserName;
    private EditText etEmail;
    private EditText etPassword;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_create_account);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        setTitle("CreateAccount");
        etUserName = findViewById();
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
    }
    // validate if UserName Format is valid
    private boolean isUserNameValid(String username){
        return username != null && username.length() >= 3;
    }
    // validate if Email format is valid
    private boolean isEmailValid(String email){
        return email != null && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }
    // validate if Password format is valid
    private boolean isPasswordValid(String password){
        return password != null && password.length() >= 4;
    }
    public void onClickCreateAccount(){
        String username = etUserName.getText().toString();
        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString();

        if(!isUserNameValid(username)){
            //error msg
            etUserName.setError("User Name invalid");
            Toast.makeText(this, "User Name invalid", Toast.LENGTH_SHORT).show();
        }
        if (!isEmailValid(email)) {
            //msgens de erro
            etEmail.setError("Email invalid");
            Toast.makeText(this, "Email invalid!", Toast.LENGTH_SHORT).show();
        }

        // Validação da password
        if (!isPasswordValid(password)) {
            //msgens de erro
            etPassword.setError("Password invalid");
            Toast.makeText(this, "Password invalid! (minimum of 6 characters)", Toast.LENGTH_SHORT).show();
        }
    }


}