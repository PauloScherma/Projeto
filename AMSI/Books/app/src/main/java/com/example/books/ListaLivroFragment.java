package com.example.books;

import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.Toast;

import com.example.books.adapters.ListaLivroAdaptador;
import com.example.books.listeners.LivrosListener;
import com.example.books.modelo.Livro;
import com.example.books.modelo.SingletonGestorLivros;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import com.example.books.adapters.ListaLivroAdaptador;

/**
 * A simple {@link Fragment} subclass.
 * Use the  factory method to
 * create an instance of this fragment.
 */
public class ListaLivroFragment extends Fragment implements LivrosListener {

    private ListView lvLivros;
    //private ArrayList<Livro> livros;



    public ListaLivroFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_lista_livro, container, false);
        setHasOptionsMenu(true);
        lvLivros = view.findViewById(R.id.lvLivros);

        //buscar os livros à API => Singleton (registar listener + pedido)
        SingletonGestorLivros.getInstance(getContext()).setLivrosListener(this);
        SingletonGestorLivros.getInstance(getContext()).getAllLivrosAPI(getContext());

        lvLivros.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(getContext(), DetalhesLivroActivity.class);
                intent.putExtra("IDLIVRO", (int) id);
                startActivityForResult(intent, MenuMainActivity.EDIT);
            }
        });

        FloatingActionButton fabLista = view.findViewById(R.id.fabLista);
        fabLista.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), DetalhesLivroActivity.class);
                startActivityForResult(intent, MenuMainActivity.ADD);
            }
        });

        return view;
    }

    @Override
    public void onCreateOptionsMenu(@NonNull Menu menu, @NonNull MenuInflater inflater) {
        super.onCreateOptionsMenu(menu, inflater);
        inflater.inflate(R.menu.menu_pesquisa,menu);

        MenuItem itemPesquisa = menu.findItem(R.id.itemPesquisa);
        SearchView searchView = (SearchView) itemPesquisa.getActionView();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            //modelo de dados
            @Override
            public boolean onQueryTextChange(String s) {
                ArrayList<Livro> tempLivros = new ArrayList<>();
                for(Livro l: SingletonGestorLivros.getInstance(getContext()).getLivros()){
                    if(l.getTitulo().toLowerCase().contains(s.toLowerCase())){
                     tempLivros.add(l);
                    }
                    //atulização da view
                    lvLivros.setAdapter(new ListaLivroAdaptador(getContext(),tempLivros));
                }
                return true;
            }

            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }
        });
    }

    /**
     *
     * @param requestCode The integer request code originally supplied to
     *                    startActivityForResult(), allowing you to identify who this
     *                    result came from.
     * @param resultCode The integer result code returned by the child activity
     *                   through its setResult().
     * @param data An Intent, which can return result data to the caller
     *               (various data can be attached to Intent "extras").
     *
     */
    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        //super.onActivityResult(requestCode, resultCode, data);
        if(requestCode == MenuMainActivity.ADD || requestCode == MenuMainActivity.EDIT){
            if(resultCode == MenuMainActivity.RESULT_OK){
            //se DeatalhesActivity tiver sinalizado REUSLT_OK

                SingletonGestorLivros.getInstance(getContext()).getAllLivrosAPI(getContext());

                switch(requestCode) {
                    case MenuMainActivity.ADD:
                        Toast.makeText(getContext(), "Livro added", Toast.LENGTH_SHORT).show();
                        break;
                    case MenuMainActivity.EDIT:
                        Toast.makeText(getContext(), "Livro edited", Toast.LENGTH_SHORT).show();
                        break;
                }
            }
        }

    }

    @Override
    public void onRefreshListaLivros(ArrayList<Livro> listaLivros) {
        if(listaLivros!=null){
            lvLivros.setAdapter(new ListaLivroAdaptador(getContext(), listaLivros));
        }
    }
}