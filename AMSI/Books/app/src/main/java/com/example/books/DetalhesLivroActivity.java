package com.example.books;

import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.books.modelo.Livro;
import com.example.books.modelo.SingletonGestorLivros;


public class DetalhesLivroActivity extends AppCompatActivity {

    private Livro livro;
    private EditText etTitulo, etSerie, etAutor, etAno;
    private ImageView imageCapa;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_detalhes_livro);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        
        etTitulo = findViewById(R.id.etTitulo);
        etSerie = findViewById(R.id.etSerie);
        etAutor = findViewById(R.id.etAutor);
        etAno = findViewById(R.id.etAno);
        imageCapa = findViewById(R.id.imageCapa);

        int idLivro = getIntent().getIntExtra("IDLIVRO", -1);

        livro = SingletonGestorLivros.getLivro(idLivro);

        if (livro != null) {
            etTitulo.setText(livro.getTitulo());
            etSerie.setText(livro.getSerie());
            etAutor.setText(livro.getAutor());
            etAno.setText(String.valueOf(livro.getAno()));
            imageCapa.setImageResource(livro.getCapa());
            setTitle("Detalhes: " + livro.getTitulo());
        }


    }
}