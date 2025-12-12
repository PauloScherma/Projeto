package com.example.books;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.example.books.listeners.LivroListener;
import com.example.books.listeners.LivrosListener;
import com.example.books.modelo.Livro;
import com.example.books.modelo.SingletonGestorLivros;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;


public class DetalhesLivroActivity extends AppCompatActivity implements LivroListener {

    private Livro livro;
    private EditText etTitulo, etSerie, etAutor, etAno;
    private ImageView imageCapa;

    public static final String DEFAULT_IMG =
            "http://amsi.dei.estg.ipleiria.pt/img/ipl_semfundo.png";


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        if(livro != null) {
            getMenuInflater().inflate(R.menu.menu_remover, menu);
        }
        return super.onCreateOptionsMenu(menu);
    }

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
        int idLivro = getIntent().getIntExtra("IDLIVRO", -1);

        livro = SingletonGestorLivros.getLivro(idLivro);
        etTitulo = findViewById(R.id.etTitulo);
        etSerie = findViewById(R.id.etSerie);
        etAutor = findViewById(R.id.etAutor);
        etAno = findViewById(R.id.etAno);
        imageCapa = findViewById(R.id.imageCapa);

        FloatingActionButton fabGuardar=findViewById(R.id.fabGuardar);

        SingletonGestorLivros.getInstance(this).setLivroListener(this);

        if (livro != null) {
            etTitulo.setText(livro.getTitulo());
            etSerie.setText(livro.getSerie());
            etAutor.setText(livro.getAutor());
            etAno.setText(String.valueOf(livro.getAno()));
            //imageCapa.setImageResource(livro.getCapa());
            Glide.with(this)
                    .load(livro.getCapa())
                    .placeholder(R.drawable.logoipl)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(imageCapa);
            setTitle("Detalhes: " + livro.getTitulo());
        }
        else{
            setTitle("Adicionar Livro");
            fabGuardar.setImageResource(R.drawable.ic_action_adicionar);
        }


        fabGuardar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String titulo = etTitulo.getText().toString();
                String serie = etSerie.getText().toString();
                String autor = etAutor.getText().toString();
                String ano = etAno.getText().toString();

                if(isLivroValido(titulo, serie, autor, ano)){
                    if(livro != null){
                        //edit
                        livro.setTitulo(titulo);
                        livro.setSerie(serie);
                        livro.setAutor(autor);
                        SingletonGestorLivros.getInstance(DetalhesLivroActivity.this).editarLivroAPI(livro, DetalhesLivroActivity.this);
                    }
                    else{
                        //criar
                        livro = new Livro(0, DEFAULT_IMG, Integer.parseInt(ano), titulo, serie, autor);
                        SingletonGestorLivros.getInstance(DetalhesLivroActivity.this).adicionarLivroAPI(livro, DetalhesLivroActivity.this);
                    }
                }
                else{
                    Toast.makeText(DetalhesLivroActivity.this, "Livro Inválido", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    private boolean isLivroValido(String titulo, String serie, String autor, String ano){
        if (titulo.length() > 3 || serie.length() > 3 || autor.length() > 3 || ano.length() == 4) {
            return true;
        } /*else if (!ano.startsWith("19") || !ano.startsWith("20")) {
            return false;
        }*/
        /*
        * else if(anoTempo<1900 || anoTemp>java.until.Calendar.getInstance().get(Calendar.Year))
        * */
        return false;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        if(item.getItemId() == R.id.itemRemover){
            dialogRemover();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void dialogRemover(){
        AlertDialog.Builder dialog = new AlertDialog.Builder(this);

        dialog.setTitle("Remover Livro")
                .setMessage("Tem a certeza que vai remover o item")
                .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        SingletonGestorLivros.getInstance(DetalhesLivroActivity.this).removerLivroAPI(livro, DetalhesLivroActivity.this);
                    }
                })
                .setNegativeButton(android.R.string.no, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        //nada
                    }
                })
                .setIcon(android.R.drawable.ic_delete)
                .show();
    }


    @Override
    public void onRefreshDetalhes() {
        setResult(RESULT_OK);
        finish();
    }
}
