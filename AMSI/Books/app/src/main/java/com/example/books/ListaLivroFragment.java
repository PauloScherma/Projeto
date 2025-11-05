package com.example.books;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import com.example.books.modelo.Livro;
import com.example.books.modelo.SingletonGestorLivros;

import java.util.ArrayList;

import adaptadores.ListaLivroAdaptador;

/**
 * A simple {@link Fragment} subclass.
 * Use the  factory method to
 * create an instance of this fragment.
 */
public class ListaLivroFragment extends Fragment {

    private ListView lvLivros;
    private ArrayList<Livro> livros;



    public ListaLivroFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_lista_livro, container, false);
        lvLivros = view.findViewById(R.id.lvLivros);
        //buscar os livros ao singleton e injetar no adaptador
        livros= SingletonGestorLivros.getInstance().getLivros();
        lvLivros.setAdapter(new ListaLivroAdaptador(getContext(),livros));

        lvLivros.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int i, long l) {
                Intent intent = new Intent(getContext(), DetalhesLivroActivity.class);
                intent.putExtra("IDLIVRO", livros.get(i).getId());
                startActivity(intent);
            }
        });

        return view;
    }
}