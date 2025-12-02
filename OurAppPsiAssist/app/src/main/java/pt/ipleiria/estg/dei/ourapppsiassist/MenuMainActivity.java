package pt.ipleiria.estg.dei.ouarppssiasist;

import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.core.view.GravityCompat;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.google.android.material.navigation.NavigationView;
import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class MenuMainActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener {

    private DrawerLayout drawer;
    private NavigationView navigationView;
    private FragmentManager fragmentManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);

        androidx.appcompat.widget.Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        drawer = findViewById(R.id.drawerLayout);
        navigationView = findViewById(R.id.nav_view);

        // Drawer toggle (hamburger icon)
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar,
                R.string.ndOpen, R.string.ndClose);

        drawer.addDrawerListener(toggle);
        toggle.syncState();

        fragmentManager = getSupportFragmentManager();

        navigationView.setNavigationItemSelectedListener(this);

        carregarCabecalho();
        carregarFragmentoInicial();
    }

    private void carregarCabecalho() {
        View headerView = navigationView.getHeaderView(0);
        TextView email = headerView.findViewById(R.id.tvEmail);

        // email passed from LoginActivity
        String emailReceived = getIntent().getStringExtra("email");
        if (email != null) {
            email.setText(emailReceived);
        }
    }

    /*private void carregarFragmentoInicial() {
        navigationView.setCheckedItem(R.id.navEstatico);
        fragmentManager.beginTransaction()
                .replace(R.id.contentFragment, new EstaticoFragment())
                .commit();
        setTitle("Livro Estático");
    }*/

    /*@Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {

        Fragment fragment = null;

        switch (item.getItemId()) {

            case R.id.navEstatico:
                fragment = new EstaticoFragment();
                setTitle(item.getTitle());
                break;

            case R.id.navDinamico:
                fragment = new DinamicoFragment();
                setTitle(item.getTitle());
                break;

            case R.id.navEmail:
                // Opens email app
                Intent emailIntent = new Intent(Intent.ACTION_SENDTO);
                emailIntent.setData(Uri.parse("mailto:example@example.com"));
                startActivity(emailIntent);
                break;
        }

        if (fragment != null) {
            fragmentManager.beginTransaction()
                    .replace(R.id.contentFragment, fragment)
                    .commit();
        }

        drawer.closeDrawer(GravityCompat.START);
        return true;*/
    }
}
