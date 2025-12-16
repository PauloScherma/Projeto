package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import com.google.android.material.navigation.NavigationView;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.DocumentsFragment;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.HomeFragment;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.ProfileFragment;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.RequestFragment;

public class MenuMainActivity extends AppCompatActivity
        implements NavigationView.OnNavigationItemSelectedListener {

    private DrawerLayout drawer;
    private NavigationView navigationView;
    private FragmentManager fragmentManager;
    private View headerView;


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
                R.string.navOpen, R.string.navClose);

        drawer.addDrawerListener(toggle);
        toggle.syncState();

        loadHeader();

        fragmentManager = getSupportFragmentManager();
        navigationView.setNavigationItemSelectedListener(this);

        loadInicialFragment();
    }

    private void loadHeader() {
        View headerView = navigationView.getHeaderView(0);
        TextView email = headerView.findViewById(R.id.tvEmail);

        // email passed from LoginActivity
        String emailReceived = getIntent().getStringExtra("email");
        if (email != null) {
            email.setText(emailReceived);
        }
    }

    private boolean loadInicialFragment() {
        Menu menu = navigationView.getMenu();
        MenuItem item = menu.getItem(0);
        item.setChecked(true);

        return onNavigationItemSelected(item);
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem menuItem) {
        Fragment fragment = null;

        if (menuItem.getItemId() == R.id.navHome) {
            fragment = new HomeFragment();
            setTitle(menuItem.getTitle());
        } else if (menuItem.getItemId() == R.id.navRequest) {
            fragment = new RequestFragment();
            setTitle(menuItem.getTitle());
        } else if (menuItem.getItemId() == R.id.navDocuments) {
            fragment = new DocumentsFragment();
            setTitle(menuItem.getTitle());
        } else if (menuItem.getItemId() == R.id.navProfile){
            fragment = new ProfileFragment();
        setTitle(menuItem.getTitle());
        }
        else if (menuItem.getItemId() == R.id.navLogout) {
            finish();
        }


        if (fragment!= null)
            fragmentManager.beginTransaction().replace(R.id.contentFragment,fragment).commit();

        drawer.closeDrawer(GravityCompat.START);
        return true;
    }
}
