package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class PasswordResetActivity extends AppCompatActivity {

    private EditText etNewPassword;
    private EditText etConfirmNewPassword;
    private Button btnChangePassword;
    private String email;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_password_reset);
        setTitle("Reset Password");

        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmNewPassword = findViewById(R.id.etConfirmNewPassword);
        btnChangePassword = findViewById(R.id.btnChangePassword);

        email = getIntent().getStringExtra("EMAIL");

        btnChangePassword.setOnClickListener(this::onClickSaveNewPassword);
    }

    private boolean isPasswordValid(String password) {
        return password != null && password.length() >= 8;
    }

    public void onClickSaveNewPassword(View view) {
        String newPassword = etNewPassword.getText().toString();
        String confirmPassword = etConfirmNewPassword.getText().toString();

        if (!isPasswordValid(newPassword) || !isPasswordValid(confirmPassword)) {
            etNewPassword.setError(getString(R.string.invalid_password));
            etConfirmNewPassword.setError(getString(R.string.invalid_password));
            Toast.makeText(this, "Invalid Password (Min 8 chars)", Toast.LENGTH_SHORT).show();
            return;
        }

        if (!newPassword.equals(confirmPassword)) {
            etConfirmNewPassword.setError("Passwords do not match");
            return;
        }

        // TODO: API PUT request to update password using `email`
        Toast.makeText(this, "Password updated successfully", Toast.LENGTH_SHORT).show();
        finish();
    }
}
