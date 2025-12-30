package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import java.util.ArrayList;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.activitys.EditProfileActivity;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Profile;
import pt.ipleiria.estg.dei.ourapppsiassist.models.SingletonRequestManager;

public class ProfileFragment extends Fragment {

    private TextView tvFirstName, tvLastName, tvEmail, tvPhoneNumber;

    public ProfileFragment() {
        // Required empty constructor
    }
    @Override
    public View onCreateView(
            @NonNull LayoutInflater inflater,
            ViewGroup container,
            Bundle savedInstanceState
    ) {
        View view = inflater.inflate(R.layout.fragment_profile, container, false);

        tvFirstName = view.findViewById(R.id.tvFirstName);
        tvLastName = view.findViewById(R.id.tvLastName);
        tvEmail = view.findViewById(R.id.tvEmail);
        tvPhoneNumber = view.findViewById(R.id.tvPhoneNumber);

        loadProfile();

        view.findViewById(R.id.btnEditProfile).setOnClickListener(v -> {
            Intent intent = new Intent(getActivity(), EditProfileActivity.class);
            startActivity(intent);
        });

        return view;
    }
    private void loadProfile() {
        ArrayList<Profile> profiles =
                SingletonRequestManager.getInstance(getContext()).getProfiles();

        if (profiles != null && !profiles.isEmpty()) {
            Profile profile = profiles.get(0);

            tvFirstName.setText(profile.getFirstName());
            tvLastName.setText(profile.getLastName());
            tvEmail.setText(profile.getEmail());
            tvPhoneNumber.setText(String.valueOf(profile.getPhoneNumber()));
        }
    }
    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == 100 && resultCode == Activity.RESULT_OK) {
            loadProfile(); // Reload updated profile
        }
    }
    @Override
    public void onResume() {
        super.onResume();
        loadProfile();
    }
}
