package com.example.books;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.provider.ContactsContract;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.graphics.Insets;
import androidx.core.view.GravityCompat;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.google.android.material.navigation.NavigationView;

public class MenuMainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    public static final int ADD = 10, EDIT = 20;

    private FragmentManager fragmentManager;
    private NavigationView navigationView;
    private DrawerLayout drawer;
    private String email;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_menu_main);

        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.navView);

        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this,
                drawer, toolbar, R.string.ndOpen, R.string.ndClose);
        toggle.syncState();
        drawer.addDrawerListener(toggle);
        carregarCabecalho();
        //fica à escuta
        navigationView.setNavigationItemSelectedListener(this);
        fragmentManager = getSupportFragmentManager();
        carregarFragmentoInicial();
    }

    private boolean carregarFragmentoInicial() {
        Menu menu = navigationView.getMenu();
        MenuItem item = menu.getItem(0);
        item.setChecked(true);
        return onNavigationItemSelected(item);
    }

    private void carregarCabecalho() {
        email = getIntent().getStringExtra("EMAIL");

        //acesso/inicialização
        SharedPreferences sharedPref = getSharedPreferences("DADOS_USER", Context.MODE_PRIVATE);

        if(email!=null){
            //escrita shared
            SharedPreferences.Editor editor = sharedPref.edit();
            editor.putString("EMAIL", email);
            editor.apply();
        }
        else{
            //leitura shared
            email = sharedPref.getString("EMAIL", "Sem email");
        }

        View hView = navigationView.getHeaderView(0);
        TextView tvEmail = hView.findViewById(R.id.tvEmail);
        tvEmail.setText(email);
    }

    @Override
    public boolean onNavigationItemSelected(MenuItem menuItem) {
        Fragment fragment = null;

        if (menuItem.getItemId()==R.id.navEstatico){
            fragment = new ListaLivroFragment();
            setTitle(menuItem.getTitle());
        }
        else if (menuItem.getItemId()==R.id.navDinamico){
            fragment = new GrelhaLivrosFragment();
            setTitle(menuItem.getTitle());
        }
        else if (menuItem.getItemId() == R.id.navEmail)
            enviarEmail();

        if (fragment!= null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment,fragment).commit();

        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    //este código é similar para varias outras funcionalidades, maps etc.
    //ver intent implicitos
    private void enviarEmail() {
        String subject = "AMSI 2025/26";
        String message = "Blablablabla";
        Intent intent = new Intent(Intent.ACTION_SEND); //destinatario
        intent.putExtra(Intent.EXTRA_EMAIL, email); //assunto
        intent.putExtra(Intent.EXTRA_SUBJECT, subject); //corpo do email
        intent.putExtra(Intent.EXTRA_TEXT, message);
        if(intent.resolveActivity(getPackageManager()) != null)
            startActivity(intent);
        else
            Toast.makeText(this, "Não tem email config", Toast.LENGTH_SHORT).show();
    }
}