package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class ForgotPasswordActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password);

        EditText emailInput = findViewById(R.id.inputEmail);
        Button sendBtn = findViewById(R.id.btnSendReset);

        sendBtn.setOnClickListener(v -> {

            String email = emailInput.getText().toString().trim();

            if (email.isEmpty()) {
                emailInput.setError(getString(R.string.email_required));
                return;
            }

            if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                emailInput.setError(getString(R.string.invalid_email_format));
                return;
            }

            // TODO: API call to verify email existence
            Intent intent = new Intent(this, PasswordResetActivity.class);
            intent.putExtra(getString(R.string.email_extra), email);
            startActivity(intent);
        });
    }
}
