package pt.ipleiria.estg.dei.ourapppsiassist.models;

public class Profile {

    private int id;
    private int userId;
    private int phoneNumber;

    private String firstName;
    private String lastName;
    private String email;
    private String createdAt;
    private String updatedAt;

    //private Availability availability;

    // --------------------------------------------------
    // Constructor
    // --------------------------------------------------
    public Profile(int id, int userId, String firstName, String lastName,
                   String email, int phoneNumber,
                   String createdAt, String updatedAt/*,
                   Availability availability)*/ {

        this.id = id;
        this.userId = userId;
        this.firstName = firstName;
        this.lastName = lastName;
        this.email = email;
        this.phoneNumber = phoneNumber;
        this.createdAt = createdAt;
        this.updatedAt = updatedAt;
        //this.availability = availability;
    }

    // --------------------------------------------------
    // Getters & Setters
    // --------------------------------------------------
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getUserId() {
        return userId;
    }

    public void setUserId(int userId) {
        this.userId = userId;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public int getPhoneNumber() {
        return phoneNumber;
    }

    public void setPhoneNumber(int phoneNumber) {
        this.phoneNumber = phoneNumber;
    }

    public String getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public String getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(String updatedAt) {
        this.updatedAt = updatedAt;
    }

//    public Availability getAvailability() {
//        return availability;
//    }
//
//    public void setAvailability(Availability availability) {
//        this.availability = availability;
//    }
}
