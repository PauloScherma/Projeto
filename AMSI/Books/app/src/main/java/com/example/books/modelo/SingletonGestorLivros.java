package com.example.books.modelo;

import com.example.books.R;

import java.util.ArrayList;

//ponto de acesso ao dados
public class SingletonGestorLivros {

    private static ArrayList<Livro> livros;
    private static SingletonGestorLivros instance = null;

    // 1 - garantir que só tenho 1 instância
    // 2 - trhed-safe => código pode ser usadp por multiplas threads se causar erros

    public static synchronized SingletonGestorLivros getInstance(){
        if(instance == null)
            instance = new SingletonGestorLivros();
        return instance;
    }

    private SingletonGestorLivros(){
        livros=new ArrayList<>();
        livros.add(new Livro(1, R.drawable.programarandroid2, 2024, "Programar em Android AMSI - 1", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(2, R.drawable.programarandroid1, 2024, "Programar em Android AMSI - 2", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(3, R.drawable.logoipl, 2024, "Programar em Android AMSI - 3", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(4, R.drawable.programarandroid2, 2024, "Programar em Android AMSI - 4", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(5, R.drawable.programarandroid1, 2024, "Programar em Android AMSI - 5", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(6, R.drawable.logoipl, 2024, "Programar em Android AMSI - 6", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(7, R.drawable.programarandroid2, 2024, "Programar em Android AMSI - 7", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(8, R.drawable.programarandroid1, 2024,"Programar em Android AMSI - 8", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(9, R.drawable.logoipl, 2024, "Programar em Android AMSI - 9", "2ª Temporada", "AMSI TEAM"));
        livros.add(new Livro(10, R.drawable.programarandroid2, 2024,  "Programar em Android AMSI - 10", "2ª Temporada", "AMSI TEAM"));
    }

    public ArrayList<Livro> getLivros(){
        return new ArrayList<>(livros);
    }

    public static Livro getLivro(int id){
        for (Livro l: livros) {
            if(l.getId()==id)
                return l;
        }
        return null;
    }

}
