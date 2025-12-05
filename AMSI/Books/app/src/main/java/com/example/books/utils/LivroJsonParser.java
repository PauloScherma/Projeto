package com.example.books.utils;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.example.books.modelo.Livro;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class LivroJsonParser {
    public static ArrayList<Livro> parserJsonLivros(JSONArray response){
        ArrayList<Livro> livros = new ArrayList<>();

        for(int i = 0; i<response.length(); i++){
            try {
                JSONObject auxLivro = (JSONObject) response.get(i);
                int id=auxLivro.getInt("id");
                int ano=auxLivro.getInt("ano");
                String titulo=auxLivro.getString("titulo");
                String serie=auxLivro.getString("serie");
                String autor=auxLivro.getString("autor");
                String capa=auxLivro.getString("capa");

                Livro livro = new Livro(id, capa, ano, titulo, serie, autor);
                livros.add(livro);

            } catch (JSONException e) {
                throw new RuntimeException(e);
            }
        }
        return livros;
    }

    public static Livro parserJsonLivro(String response){

        Livro livro=null;

            try {
                //atenção a linha de baixo cast para Strin foi uma sugestão do IDE
                JSONObject auxLivro = new JSONObject(response);
                int id=auxLivro.getInt("id");
                int ano=auxLivro.getInt("ano");
                String titulo=auxLivro.getString("titulo");
                String serie=auxLivro.getString("serie");
                String autor=auxLivro.getString("autor");
                String capa=auxLivro.getString("capa");

                livro = new Livro(id, capa, ano, titulo, serie, autor);

            } catch (JSONException e) {
                throw new RuntimeException(e);
            }

        return livro;
    }

    public static String parserJsonLogin(){
        //TODO:

        return null;
    }

    public static boolean isConnectionInternet(Context context){
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        if(cm!=null) {
            NetworkInfo ni = cm.getActiveNetworkInfo();
            return ni!=null && ni.isConnected();
        }
        return false;
    }
}
