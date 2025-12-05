package com.example.books;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.example.books.modelo.Livro;
import com.example.books.modelo.SingletonGestorLivros;

import java.util.ArrayList;

public class DinamicoFragment extends Fragment {

    private TextView tvTituloConteudo, tvSerieConteudo, tvAutorConteudo, tvAnoConteudo;

    public DinamicoFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_dinamico, container, false);

        tvTituloConteudo = view.findViewById(R.id.tvTituloConteudo);
        tvSerieConteudo = view.findViewById(R.id.tvSerieConteudo);
        tvAutorConteudo = view.findViewById(R.id.tvAutorConteudo);
        tvAnoConteudo = view.findViewById(R.id.tvAnoConteudo);

        carregarLivro();

        return view;
    }

    private void carregarLivro() {
        ArrayList<Livro> livros = SingletonGestorLivros.getInstance(getContext()).getLivros();
        Livro livro;
        if (livros != null && livros.size() > 0) {
            livro = livros.get(0);
            tvTituloConteudo.setText(livro.getTitulo());
            tvSerieConteudo.setText(livro.getSerie());
            tvAutorConteudo.setText(livro.getAutor());
            tvAnoConteudo.setText(livro.getAno() + "");
        }
    }
}