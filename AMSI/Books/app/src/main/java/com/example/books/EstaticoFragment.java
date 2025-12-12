package com.example.books;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

public class EstaticoFragment extends Fragment {

    public EstaticoFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_estatico,container,false);
        TextView tvTituloConteudo=view.findViewById(R.id.tvTituloConteudo);
        TextView tvSerieConteudo=view.findViewById(R.id.tvSerieConteudo);
        TextView tvAutorConteudo=view.findViewById(R.id.tvAutorConteudo);
        TextView tvAnoConteudo=view.findViewById(R.id.tvAnoConteudo);

        tvTituloConteudo.setText("Teste Titulo Bem Sucedido");
        tvSerieConteudo.setText("Teste Serie Bem Sucedido");
        tvAutorConteudo.setText("Test Autor Bem Sucedido");
        tvAnoConteudo.setText("Teste Ano Bem Sucedido");

        return view;

    }
}