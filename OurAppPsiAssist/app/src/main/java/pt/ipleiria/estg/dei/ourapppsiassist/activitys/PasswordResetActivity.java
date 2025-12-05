package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import static pt.ipleiria.estg.dei.ourapppsiassist.R.string.invalid_password;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class PasswordResetActivity extends AppCompatActivity {
    private EditText etNewPassword;
    private EditText etConfirmNewPassword;
    private Button btnChangePassword;
    String email; // <-- Coming from previous activity

    @SuppressLint("MissingInflatedId")
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
        setTitle("Reset Password");
        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmNewPassword = findViewById(R.id.etConfirmNewPassword);
    }
    // validate passswords format
    private boolean isPasswordValid(String password){
        return password != null && password.length() >= 8;
    }
    // on click event to reset the password
    public void onClickResetPassword(){
        String newpassword = etNewPassword.getText().toString();
        String confirmnewpassword = etConfirmNewPassword.getText().toString();

        // if passwords format are not valid send error msg
        if(!isPasswordValid(newpassword) || !isPasswordValid(confirmnewpassword)){
            // error msg
            etNewPassword.setError(getString(invalid_password));
            etConfirmNewPassword.setError("invalid_password");
            Toast.makeText(this, "Invalid Password (Min 8 chars)", Toast.LENGTH_SHORT).show();
        }
        // if password format is valid compare the two
        if(isPasswordValid(newpassword) && isPasswordValid(confirmnewpassword)){
            if(newpassword == confirmnewpassword){
                // TODO api request for password
                // TODO api put for password seting

            }
        }


    }

}