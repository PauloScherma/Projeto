package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.os.Bundle;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;
import pt.ipleiria.estg.dei.ourapppsiassist.models.SingletonRequestManager;

public class RequestActivity extends AppCompatActivity
        implements pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestListener {

    private Request request;
    private EditText etTitle, etDescription;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_create_request);

        etTitle = findViewById(R.id.etTitle);
        etDescription = findViewById(R.id.etDescription);

        int idRequest = getIntent().getIntExtra("IDREQUEST", -1);
        request = SingletonRequestManager.getRequest(idRequest);

        SingletonRequestManager.getInstance(this).setRequestListener(this);

        FloatingActionButton fabAdd = findViewById(R.id.fabAdd);

        if (request != null) {
            etTitle.setText(request.getTitle());
            etDescription.setText(request.getDescription());
            setTitle("Details: " + request.getTitle());
        } else {
            setTitle("New Request");
        }

        fabAdd.setOnClickListener(v -> {

            String title = etTitle.getText().toString().trim();
            String description = etDescription.getText().toString().trim();

            if (title.isEmpty()) {
                etTitle.setError("Título obrigatório");
                return;
            }

            if (request != null) {
                // EDITAR
                request.setTitle(title);
                request.setDescription(description);

                SingletonRequestManager.getInstance(this)
                        .editRequestAPI(request, this);

            } else {
                request = new Request(
                        0,
                        1,              // mudar para o ID do utilizador
                        title,
                        "new",
                        description,
                        null,
                        null
                );

                SingletonRequestManager.getInstance(this)
                        .addRequestAPI(request, this);
            }
        });
    }

    @Override
    public void onUpdateRequest() {
        setResult(RESULT_OK);
        finish();
    }

    @Override
    public void onRefreshDetalhes() {
        setResult(RESULT_OK);
        finish();
    }

    @Override
    public void onError(String message) {
        // podes mostrar Toast se quiseres
    }

    @Override
    public void onRefreshDetails() {
        // opcional
    }
}
