package pt.ipleiria.estg.dei.ourapppsiassist.listeners;

public interface RequestListener {
    void onUpdateRequest();
    void onError(String message);

    void onRefreshDetails();
}
