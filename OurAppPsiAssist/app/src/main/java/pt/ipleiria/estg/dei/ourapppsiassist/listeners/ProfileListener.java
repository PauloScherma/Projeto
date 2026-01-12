package pt.ipleiria.estg.dei.ourapppsiassist.listeners;

public interface ProfileListener {
    void onRefreshDetalhes();
    void onUpdateRequest();
    void onError(String message);
    void onRefreshDetails();
    void onUpdateProfile();
}
