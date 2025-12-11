package com.example.books.modelo;

import android.annotation.SuppressLint;
import android.content.Context;
import android.widget.Toast;

import androidx.annotation.Nullable;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.books.MenuMainActivity;
import com.example.books.listeners.LivroListener;
import com.example.books.listeners.LivrosListener;
import com.example.books.utils.LivroJsonParser;

import org.json.JSONArray;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

//ponto de acesso ao dados
public class SingletonGestorLivros {
    private static ArrayList<Livro> livros;
    private static SingletonGestorLivros instance = null;
    private LivroBDHelper livroBD;
    private static RequestQueue volleyQueue = null;
    private static final String mUrlAPILivros = "http://amsi.dei.estg.ipleiria.pt/api/livros";
   // private static final String mUrlAPILivros ="http://172.22.21.41/api/livros";
    private static final String TOKEN = "AMSI-TOKEN";
    private LivrosListener livrosListener;
    private LivroListener livroListener;

    // 1 - garantir que só tenho 1 instância
    // 2 - trhed-safe => código pode ser usadp por multiplas threads se causar erros

    public static synchronized SingletonGestorLivros getInstance(Context context){
        if(instance == null) {
            instance = new SingletonGestorLivros(context);
            volleyQueue = Volley.newRequestQueue(context);
        }
        return instance;
    }

    private SingletonGestorLivros(Context context){
        livros=new ArrayList<>();
        livroBD= new LivroBDHelper(context);
    }

    public void setLivroListener(LivroListener livroListener) {
        this.livroListener = livroListener;
    }

    public void setLivrosListener(LivrosListener livrosListener) {
        this.livrosListener = livrosListener;
    }

    public ArrayList<Livro> getLivros(){
        livros = livroBD.getAllLivrosBD();
        return new ArrayList<>(livros);
    }

    public static Livro getLivro(int id){
        for (Livro l: livros) {
            if(l.getId()==id)
                return l;
        }
        return null;
    }

    //region Crud bd livro
    public void adicionarLivroBD(Livro livro){
        livroBD.adicionarLivro(livro);
   /*     if(auxLivro!=null)
            livros.add(livro);*/
    }
    public void adicionarLivrosBD(ArrayList<Livro> livros){
        livroBD.removerAllLivroDB();
        for(Livro l:livros){
            adicionarLivroBD(l);
        }
    }

    public void editarLivroBD(Livro livro){
        Livro l=getLivro(livro.getId());
        if(l!=null){
            livroBD.editarLivro(livro);
          /*  if(op){
                l.setTitulo(livro.getTitulo());
                l.setAutor(livro.getAutor());
                l.setSerie(livro.getSerie());
                l.setAno(livro.getAno());
            }*/
        }
    }
    public void removerLivroBD(int idLivro){
       Livro l= getLivro(idLivro);
       if(l!=null) {
          livroBD.removerLivro(idLivro);
       /*    if(op)
               livros.remove(l);*/
       }
    }
    //endregion

    //region Acesso à API (Volley)

    public void adicionarLivroAPI(final Livro livro, final Context context){
        if(!LivroJsonParser.isConnectionInternet(context)){
            Toast.makeText(context, "No internet connection!!!", Toast.LENGTH_SHORT).show();
        }
        else{
            StringRequest req = new StringRequest(Request.Method.POST, mUrlAPILivros, new Response.Listener<String>() {
                @Override
                public void onResponse(String s) {
                    //erro!!!
                    adicionarLivroBD(LivroJsonParser.parserJsonLivro(s));
                    if(livroListener!=null){
                        livroListener.onRefreshDetalhes();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError volleyError) {
                    Toast.makeText(context, volleyError.getMessage(), Toast.LENGTH_SHORT).show();
                }
            })
            {
                @Nullable
                @Override
                protected Map<String, String> getParams(){
                    Map<String, String> params = new HashMap<>();
                    params.put("token", TOKEN);
                    params.put("titulo", livro.getTitulo());
                    params.put("autor", livro.getAutor());
                    params.put("serie", livro.getSerie());
                    params.put("capa", livro.getCapa());
                    params.put("ano", livro.getAno()+"");
                    return params;
                }
            };
            volleyQueue.add(req);
        }
    }
    public void editarLivroAPI(final Livro livro, final Context context){
        if(!LivroJsonParser.isConnectionInternet(context)){
            Toast.makeText(context, "No internet connection!!!", Toast.LENGTH_SHORT).show();
        }
        else{
            StringRequest req = new StringRequest(Request.Method.PUT, mUrlAPILivros + '/' + livro.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String s) {
                    editarLivroBD(livro);
                    if(livroListener!=null){
                        livroListener.onRefreshDetalhes();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError volleyError) {
                    Toast.makeText(context, volleyError.getMessage(), Toast.LENGTH_SHORT).show();
                }
            })
            {
                @Nullable
                @Override
                protected Map<String, String> getParams(){
                    Map<String, String> params = new HashMap<>();
                    params.put("token", TOKEN);
                    params.put("titulo", livro.getTitulo());
                    params.put("autor", livro.getAutor());
                    params.put("serie", livro.getSerie());
                    params.put("capa", livro.getCapa());
                    params.put("ano", livro.getAno()+"");
                    return params;
                }
            };
            volleyQueue.add(req);
        }
    }
    public void removerLivroAPI(final Livro livro, final Context context){
        if(!LivroJsonParser.isConnectionInternet(context)){
            Toast.makeText(context, "No internet connection!!!", Toast.LENGTH_SHORT).show();
        }
        else{
            StringRequest req = new StringRequest(Request.Method.DELETE, mUrlAPILivros + '/' + livro.getId(), new Response.Listener<String>() {
                @Override
                public void onResponse(String s) {
                    removerLivroBD(livro.getId());
                    if(livroListener!=null){
                        livroListener.onRefreshDetalhes();
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError volleyError) {
                    Toast.makeText(context, volleyError.getMessage(), Toast.LENGTH_SHORT).show();
                }
            });
            {
                volleyQueue.add(req);
            }
        }
    }
    public void getAllLivrosAPI(final Context context){
        if(!LivroJsonParser.isConnectionInternet(context)){
            Toast.makeText(context, "No internet connection!!!", Toast.LENGTH_SHORT).show();
            //TODO: apresentar os livros da bd=>informar vista
        }
        else{
            JsonArrayRequest req =new JsonArrayRequest(Request.Method.GET, mUrlAPILivros, null,
               new Response.Listener<JSONArray>() {
                @Override
                public void onResponse(JSONArray jsonArray) {
                    livros = LivroJsonParser.parserJsonLivros(jsonArray);
                    adicionarLivrosBD(livros);
                    //informar a vista que se registou um listener
                    if(livrosListener!=null){
                        livrosListener.onRefreshListaLivros(livros);
                    }
                }
            }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError volleyError) {
                    Toast.makeText(context, volleyError.getMessage(), Toast.LENGTH_SHORT).show();
                }
            });
            volleyQueue.add(req);
        }
    }
    //endregion


}
