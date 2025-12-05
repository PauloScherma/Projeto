package com.example.books.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.example.books.R;
import com.example.books.modelo.Livro;

import java.util.ArrayList;

public class ListaLivroAdaptador extends BaseAdapter {

    private Context context;

    private LayoutInflater layoutInflater;

    private ArrayList<Livro> livros;

    public ListaLivroAdaptador(Context context, ArrayList<Livro> livros) {
        this.context = context;
        this.livros = livros;
    }

    //numero de elementos da coleção
    @Override
    public int getCount() {
        return livros.size();
    }

    //Devolve um livro que está em determinada posição do array
    @Override
    public Object getItem(int i) {
        return livros.get(i);
    }

    //Devolve o id do livro que está em determinada posição do array
    @Override
    public long getItemId(int i) {
        return livros.get(i).getId();
    }

    //Carregar a vista => vamos otimizar de forma a reutilizar o layout de cada item em vez de estar sempre a criar um novo
    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        if(layoutInflater==null)
            layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        if(view==null)
            view=layoutInflater.inflate(R.layout.item_lista_livro, null);

        //otimização: após carregar o item uma vez, já não precisa de carregar nomavemente
        ViewHolderLista viewHolder= (ViewHolderLista) view.getTag();
        if(viewHolder==null){
            viewHolder = new ViewHolderLista(view);
            view.setTag(viewHolder);
        }
        //só o conteudo od livro é que vai ser carregado multiplas vezes
        viewHolder.uptade(livros.get(i));

        return view;
    }

    private class ViewHolderLista{
        //elementos gráficos que constam do itemListaLivro
        private TextView tvTitulo, tvSerie, tvAno, tvAutor;
        private ImageView imgCapa;
        public ViewHolderLista(View view){
            tvTitulo = view.findViewById(R.id.tvTitulo);
            tvSerie = view.findViewById(R.id.tvSerie);
            tvAno = view.findViewById(R.id.tvAno);
            tvAutor = view.findViewById(R.id.tvAutor);
            imgCapa = view.findViewById(R.id.imgCapa);
        }

        public void uptade(Livro livro){
            tvTitulo.setText(livro.getTitulo());
            tvSerie.setText(livro.getSerie());
            tvAutor.setText(livro.getAutor());
            tvAno.setText(livro.getAno()+"");
            //imgCapa.setImageResource(livro.getCapa());
            Glide.with(context)
                    .load(livro.getCapa())
                    .placeholder(R.drawable.logoipl)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .into(imgCapa);
        }
    }
}
