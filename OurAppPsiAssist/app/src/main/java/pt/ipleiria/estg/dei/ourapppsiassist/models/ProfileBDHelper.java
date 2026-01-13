package pt.ipleiria.estg.dei.ourapppsiassist.models;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;

public class ProfileBDHelper extends SQLiteOpenHelper {

    private static final String DB_NAME = "dbprojeto_v1.db";
    private static final int DB_VERSION = 4;
    private static final String TABLE_NAME = "profile";
    private static final String COLUMN_ID = "id";
    private static final String COLUMN_USER_ID = "user_id";
    private static final String COLUMN_FIRST_NAME = "first_name";
    private static final String COLUMN_LAST_NAME = "last_name";
    private static final String COLUMN_PHONE = "phone";
    private static final String COLUMN_CREATED_AT = "created_at";
    private static final String COLUMN_UPDATED_AT = "updated_at";

    public ProfileBDHelper(Context context) {
        super(context, DB_NAME, null, DB_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String sql = "CREATE TABLE " + TABLE_NAME + " (" +
                COLUMN_ID + " INTEGER PRIMARY KEY, " +
                COLUMN_USER_ID + " INTEGER NOT NULL, " +
                COLUMN_FIRST_NAME + " TEXT NOT NULL, " +
                COLUMN_LAST_NAME + " TEXT NOT NULL, " +
                COLUMN_PHONE + " TEXT, " +
                COLUMN_CREATED_AT + " TEXT NOT NULL, " +
                COLUMN_UPDATED_AT + " TEXT" +
                ");";

        db.execSQL(sql);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_NAME);
        onCreate(db);
    }

    //region CRUD: Create (Adicionar)
    public long addProfile(Profile p) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(COLUMN_ID, p.getId());
        values.put(COLUMN_USER_ID, p.getUserId());
        values.put(COLUMN_FIRST_NAME, p.getFirstName());
        values.put(COLUMN_LAST_NAME, p.getLastName());
        values.put(COLUMN_PHONE, p.getPhoneNumber());
        values.put(COLUMN_CREATED_AT, p.getCreatedAt());
        values.put(COLUMN_UPDATED_AT, p.getUpdatedAt());

        long id = db.insert(TABLE_NAME, null, values);
        db.close();
        return id;
    }
    //endregion

    //region CRUD: Read (Ler todos)
    public ArrayList<Profile> getAllProfilesDB() {
        ArrayList<Profile> profiles = new ArrayList<>();
        SQLiteDatabase db = getReadableDatabase();
        Cursor cursor = db.query(TABLE_NAME, null, null, null, null, null, COLUMN_ID + " DESC");

        if (cursor.moveToFirst()) {
            do {
                Profile p = new Profile(
                        cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_ID)),
                        cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_USER_ID)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_FIRST_NAME)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_LAST_NAME)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_PHONE)),
                        cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_CREATED_AT))
                );
                p.setUpdatedAt(cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_UPDATED_AT)));
                profiles.add(p);
            } while (cursor.moveToNext());
        }

        cursor.close();
        db.close();
        return profiles;
    }
    //endregion

    //region CRUD: Update (Editar)
    public boolean editProfile(Profile p) {
        SQLiteDatabase db = getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put(COLUMN_USER_ID, p.getUserId());
        values.put(COLUMN_FIRST_NAME, p.getFirstName());
        values.put(COLUMN_LAST_NAME, p.getLastName());
        values.put(COLUMN_PHONE, p.getPhoneNumber());
        values.put(COLUMN_CREATED_AT, p.getCreatedAt());
        values.put(COLUMN_UPDATED_AT, p.getUpdatedAt());

        int rows = db.update(TABLE_NAME, values, COLUMN_ID + "=?", new String[]{String.valueOf(p.getId())});
        db.close();
        return rows > 0;
    }
    //endregion

    //region CRUD: Delete (Remover)
    public boolean removeProfile(int id) {
        SQLiteDatabase db = getWritableDatabase();
        int rows = db.delete(TABLE_NAME, COLUMN_ID + "=?", new String[]{String.valueOf(id)});
        db.close();
        return rows > 0;
    }
    //endregion

    //region Apagar todos os registos
    public void removeAllProfilesDB() {
        SQLiteDatabase db = getWritableDatabase();
        db.delete(TABLE_NAME, null, null);
        db.close();
    }
    //endregion
}
