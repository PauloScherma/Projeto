package com.example.books.listeners;

import com.example.books.modelo.Livro;
import java.util.ArrayList;

public interface LivrosListener
{
    //Tem como objetivo atualizar a coleçao de livros
    //ListaLivroFragment e GrelhaLivroFragment devem implementar esta interface
    void onRefreshListaLivros(ArrayList<Livro> listaLivros);
}
