package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class ForgotPasswordActivity extends AppCompatActivity {

    private EditText emailInput;
    private Button sendBtn;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_forgot_password);

        emailInput = findViewById(R.id.inputEmail);
        sendBtn = findViewById(R.id.btnSendReset);

        sendBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                String email = emailInput.getText().toString().trim();

                if (email.isEmpty()) {
                    emailInput.setError("Email required");
                    return;
                }


                // TODO: API call here to check if email exists

                boolean emailExistsInDatabase = true; // <-- Replace with backend result

                if (emailExistsInDatabase) {
                    // Move to reset password screen
                    Intent intent = new Intent(ForgotPasswordActivity.this, PasswordResetActivity.class);
                    intent.putExtra("email", email);
                    startActivity(intent);

                } else {
                    Toast.makeText(ForgotPasswordActivity.this, "Invalid Email", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    public void onclickSendCode(View view) {
    }
}

