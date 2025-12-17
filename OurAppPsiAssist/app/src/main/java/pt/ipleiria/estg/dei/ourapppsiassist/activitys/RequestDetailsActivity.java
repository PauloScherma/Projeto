package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.os.Bundle;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;
import pt.ipleiria.estg.dei.ourapppsiassist.models.SingletonRequestManager;

public class RequestDetailsActivity extends AppCompatActivity
        implements pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestListener {
    private Request request;
    private EditText etTitle, etDescription, etStatus, etCreatedAt;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details_request);

        etTitle = findViewById(R.id.etTitle);
        etDescription = findViewById(R.id.etDescription);
        etStatus = findViewById(R.id.etStatus);
        etCreatedAt = findViewById(R.id.etCreatedAt);

        int idRequest = getIntent().getIntExtra("IDREQUEST", -1);
        request = SingletonRequestManager.getRequest(idRequest);

        SingletonRequestManager.getInstance(this).setRequestListener(this);

        FloatingActionButton fabSave = findViewById(R.id.fabGuardar);

        if (request != null) {
            etTitle.setText(request.getTitle());
            etDescription.setText(request.getDescription());
            etStatus.setText(request.getStatus());
            etCreatedAt.setText(request.getCreated_at());
            setTitle("Details: " + request.getTitle());
        } else {
            setTitle("New Request");
        }

        fabSave.setOnClickListener(v -> {
            String title = etTitle.getText().toString();
            String description = etDescription.getText().toString();
            String status = etStatus.getText().toString();
            String createdAt = etCreatedAt.getText().toString();
            String updatedAt = createdAt;

            if (request != null) {
                request.setTitle(title);
                request.setDescription(description);
                request.setStatus(status);
                request.setCreated_at(createdAt);
                request.setUpdated_at(updatedAt);

                SingletonRequestManager.getInstance(this)
                        .editRequestAPI(request, this);
            } else {
                request = new Request(
                        0,
                        1,
                        title,
                        status,
                        description,
                        createdAt,
                        updatedAt
                );

                SingletonRequestManager.getInstance(this)
                        .addRequestAPI(request, this);
            }
        });
    }

    @Override
    public void onRefreshDetalhes() {
        setResult(RESULT_OK);
        finish();
    }

    @Override
    public void onUpdateRequest() {
        setResult(RESULT_OK);
        finish();
    }

    @Override
    public void onError(String message) {
        // Recommended minimum feedback
        // Toast.makeText(this, message, Toast.LENGTH_LONG).show();
    }

    @Override
    public void onRefreshDetails() {
        // Optional, depending on your interface design
    }

}
