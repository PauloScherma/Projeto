package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.ProfileFragment;

public class EditProfileActivity extends AppCompatActivity {

    private EditText etFirstName, etLastName, etEmail, etPhoneNumber;
    private Button btnSave;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_profile);
        setTitle("Edit Profile");

        etFirstName = findViewById(R.id.etFirstName);
        etLastName = findViewById(R.id.etLastName);
        etEmail = findViewById(R.id.etEmail);
        etPhoneNumber = findViewById(R.id.etPhoneNumber);
        btnSave = findViewById(R.id.btnSave);

        btnSave.setOnClickListener(this::onClickSave);
    }
    private boolean isNameValid(String username) {
        return username != null && username.length() >= 3;
    }
    private boolean isEmailValid(String email) {
        return email != null && Patterns.EMAIL_ADDRESS.matcher(email).matches();
    }
    private boolean isPhoneNumberValid(String phoneNumber) {
        return phoneNumber != null && phoneNumber.length() >= 3;
    }

    public void onClickSave(View view) {
        String firstname = etFirstName.getText().toString().trim();
        String lastname = etLastName.getText().toString().trim();
        String email = etEmail.getText().toString().trim();
        String phoneNumber = etPhoneNumber.getText().toString().trim();

        if (!isNameValid(firstname)) {
            etFirstName.setError("Name must have at least 3 characters");
            return;
        }
        if (!isNameValid(lastname)) {
            etLastName.setError("Name must have at least 3 characters");
            return;
        }
        if (!isEmailValid(email)) {
            etEmail.setError("Invalid email");
            return;
        }
        if (!isPhoneNumberValid(phoneNumber)) {
            etPhoneNumber.setError("Invalid phone number");
            return;
        }

        // TODO: Save profile changes (API / DB)

        Toast.makeText(this, "Profile updated!", Toast.LENGTH_SHORT).show();
        setResult(RESULT_OK);
        finish(); // returns to ProfileFragment
    }
}
