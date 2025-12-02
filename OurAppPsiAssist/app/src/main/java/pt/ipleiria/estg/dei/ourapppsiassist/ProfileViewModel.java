package pt.ipleiria.estg.dei.ourapppsiassist;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class ProfileViewModel extends ViewModel {
    // TODO: Implement the ViewModel
    private final ApiRepository repository = new ApiRepository();
    private final MutableLiveData<String> data = new MutableLiveData<>();

    private final ExecutorService executor = Executors.newSingleThreadExecutor();

    public LiveData<String> getData() {
        return data;
    }

    public void loadData() {
        executor.execute(() -> {
            String result = repository.getInfo();
            data.postValue(result);
        });
    }

    public void getProfileData() {
    }

    public void loadProfile() {

    }
}

// Example repository
class ApiRepository {
    public String getInfo() {
        // Replace this with your actual API call
        return "Some data from API";
    }
}