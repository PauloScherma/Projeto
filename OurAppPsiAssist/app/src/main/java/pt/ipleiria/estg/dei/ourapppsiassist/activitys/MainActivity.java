package pt.ipleiria.estg.dei.ourapppsiassist.activitys;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.DocumentsFragment;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.HomeFragment;
import pt.ipleiria.estg.dei.ourapppsiassist.fragments.RequestFragment;

public class MainActivity extends AppCompatActivity {

    private FragmentManager fragmentManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_main);
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });
        if (savedInstanceState == null) {
            getSupportFragmentManager().beginTransaction()
                    .replace(R.id.fragment_container, new HomeFragment())
                    .commit();
        }
    }

    public void onClickDocs(View view) {
    DocumentsFragment docsFragmnet = new DocumentsFragment();

        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.fragment_container, docsFragmnet )
                .addToBackStack(null) // allows back button
                .commit();
    }
    public void onClickGoToRequests(View view) {
        RequestFragment requestFragment = new RequestFragment();

        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.fragment_container, requestFragment )
                .addToBackStack(null) // allows back button
                .commit();
    }
    public void onClickGoHome(View view) {
        HomeFragment homeFragment = new HomeFragment();

        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.fragment_container, homeFragment )
                .addToBackStack(null) // allows back button
                .commit();
    }


    public void onClickOpenMenu(View view) {
        Intent intent = new Intent(MainActivity.this, MenuMainActivity.class);
        startActivity(intent);
    }

    public void onClickAppSettings(View view) {
    }
}