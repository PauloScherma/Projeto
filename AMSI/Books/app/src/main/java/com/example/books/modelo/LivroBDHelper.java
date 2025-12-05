package com.example.books.modelo;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import androidx.annotation.Nullable;

import java.lang.reflect.Array;
import java.util.ArrayList;

public class LivroBDHelper extends SQLiteOpenHelper {

    private static final String DB_NOME="dbLivroPL1";
    private static final String TABELA_NOME="livros";
    private static final String ID="id", TITULO="titulo", SERIE="serie", AUTOR="autor", ANO="ano", CAPA="capa";
    private static final int DB_VERSAO=1;
    private final SQLiteDatabase db;

    public LivroBDHelper(@Nullable Context context) {
        super(context, DB_NOME, null, DB_VERSAO);
        db = getWritableDatabase();
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        String sqlTabelaLivro = " create table " + TABELA_NOME
                + "(" + ID + " INTEGER PRIMARY KEY, "
                + TITULO + " TEXT NOT NULL, "
                + SERIE + " TEXT NOT NULL, "
                + AUTOR + " TEXT NOT NULL, "
                + ANO + " INT NOT NULL, "
                + CAPA + " TEXT "
                + ")";
        sqLiteDatabase.execSQL(sqlTabelaLivro);
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int oldVersion, int newVersion) {
        sqLiteDatabase.execSQL(" DROP TABLE IF EXISTS " + TABELA_NOME);
        this.onCreate(sqLiteDatabase);
    }

    //region CRUD - BD LOCAL

    public Livro adicionarLivro(Livro l){
        ContentValues values=new ContentValues();
        values.put(ID, l.getId());
        values.put(TITULO, l.getTitulo());
        values.put(CAPA, l.getCapa());
        values.put(SERIE, l.getSerie());
        values.put(AUTOR, l.getAutor());
        values.put(ANO, l.getAno());

        //devolve o id do livro criado ou -1 em caso de erro
        long id=db.insert(TABELA_NOME, null, values);

        if(id>-1){
            return l;
        }

        return null;
    }

    public boolean editarLivro(Livro l){
        ContentValues values=new ContentValues();
        values.put(TITULO, l.getTitulo());
        values.put(CAPA, l.getCapa());
        values.put(SERIE, l.getSerie());
        values.put(AUTOR, l.getAutor());
        values.put(ANO, l.getAno());

        //devolve o n de linhas alteradas
        long numLinhas=db.update(TABELA_NOME, values, ID+"=?", new String[]{l.getId()+""});

        return numLinhas>0;
    }

    public boolean removerLivro(int id){
        long numLinhas=db.delete(TABELA_NOME,ID+"=?", new String[]{id+""});
        return numLinhas>0;
    }

    public void removerAllLivroDB(){
        db.delete(TABELA_NOME,null, null);
    }


    public ArrayList<Livro> getAllLivrosBD(){
        ArrayList<Livro> livros = new ArrayList<>();

        Cursor cursor=db.query(TABELA_NOME, new String[]{ID, CAPA, ANO, TITULO, SERIE, AUTOR}, null, null,
                null,null,null);

        //não usar rawQuerys!!! exemplo:
        //Cursor cursor2=db.rawQuery("SELECT * FROM livros WHERE id <10", null);

        if(cursor.moveToFirst()){
            do {
                Livro auxLivro=new Livro(cursor.getInt(0), cursor.getString(1), cursor.getInt(2),
                        cursor.getString(3), cursor.getString(4), cursor.getString(5));
                livros.add(auxLivro);
            }while(cursor.moveToNext());

            cursor.close();
        }

        return livros;
    }

    //endregion
}
