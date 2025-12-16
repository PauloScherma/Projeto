package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import androidx.fragment.app.Fragment;

public class ProfileFragment extends Fragment {

    /*private ProfileViewModel viewModel;
    private TextView profileTextView;

    @SuppressLint("MissingInflatedId")
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_profile, container, false);
        profileTextView = view.findViewById(R.id.btnProfile);

        // Get ViewModel
        viewModel = new ViewModelProvider(this).get(ProfileViewModel.class);

        // Observe LiveData
        viewModel.getData().observe(getViewLifecycleOwner(), new Observer<String>() {
            @Override
            public void onChanged(String data) {
                profileTextView.setText(data);
            }
        });

        // Load data
        viewModel.loadProfile();

        return view;
    }*/
}
